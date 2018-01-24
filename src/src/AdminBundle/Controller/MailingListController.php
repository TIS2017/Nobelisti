<?php

namespace AdminBundle\Controller;

//TODO
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Attendee;
// potrbeujem??
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MailingListController extends Controller
{
    /**
     * @Route("/mailing_list/{page}", name="mailing_list", defaults={"page"=1})
     * @Method("GET")
     */
    public function indexAction($page, Request $request)
    {
        $numItemsPerPage = 3;
        $mailingList = [];
        $defaultData = array('nameEmail' => '', 'event' => '', 'isSubscribed' => false);
        $form = $this->createFormBuilder($defaultData)
            ->add('nameEmail', TextType::class, array(
                'label' => 'Name / Email',
                'required' => false,
            ))
            ->add('event', TextType::class, array(
                'required' => false,
            ))
            ->add('isSubscribed', CheckboxType::class, array(
                'label' => 'Only subscribed',
                'required' => false,
            ))
            ->add('filter', SubmitType::class, array(
                'label' => 'Filter',
            ))
            ->setMethod('GET')
            ->getForm();

        $form->handleRequest($request);

        $repositoryAttendee = $this->getDoctrine()->getRepository(Attendee::class);
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $qb->select('count(a.id)');
        $qb->from('AdminBundle:Attendee', 'a');

        if ($form->isSubmitted() && $form->isValid()) {
            $searchedNameEmail = $form->getData()['nameEmail'];
            $searchedEvent = $form->getData()['event'];
            $searchedIsSubscribed = $form->getData()['isSubscribed'];

            $mailingList = $em->getRepository(Attendee::class)
            ->createQueryBuilder('a')
            ->where('(a.email LIKE :name OR a.firstName LIKE :name OR a.lastName LIKE :name) AND a.unsubscribed LIKE :subsc')
            ->setParameter('name', '%'.$searchedNameEmail.'%')
            ->setParameter('subsc', '%'.$searchedIsSubscribed.'%')
            ->orderBy('a.id', 'ASC')
            ->setFirstResult(($page - 1) * $numItemsPerPage)
            ->setMaxResults($numItemsPerPage)
            ->getQuery()
            ->getResult();

            $countPages = ceil((
                $em->createQueryBuilder()
                ->select('count(a.id)')
                ->from('AdminBundle:Attendee', 'a')
                ->where('(a.email LIKE :name OR a.firstName LIKE :name OR a.lastName LIKE :name) AND a.unsubscribed LIKE :subsc')
                ->setParameter('name', '%'.$searchedNameEmail.'%')
                ->setParameter('subsc', '%'.$searchedIsSubscribed.'%')
                ->getQuery()
                ->getSingleScalarResult()
                ) / $numItemsPerPage);
        } else {
            $mailingList = $repositoryAttendee->findBy(array(), array(), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
            $countPages = ceil(($qb->getQuery()->getSingleScalarResult()) / $numItemsPerPage);
        }

        return $this->render('AdminBundle:MailingList:index.html.twig', array(
            'form' => $form->createView(),
            'mailingList' => $mailingList,
            'currentPage' => $page,
            'countPages' => $countPages,
            'numItemsPerPage' => $numItemsPerPage,
        ));
    }
}
