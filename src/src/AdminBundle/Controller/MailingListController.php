<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Registration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\Query\Expr\Join;

class MailingListController extends Controller
{
    /**
     * @Route("/mailing_list/{page}", name="mailing_list", defaults={"page"=1})
     * @Method("GET")
     */
    public function indexAction($page, Request $request)
    {
        $numItemsPerPage = 3;
        $countPages = 0;
        $mailingList = [];
        $em = $this->getDoctrine()->getManager();
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
            ->setAction($this->generateUrl('mailing_list'))
            ->setMethod('GET')
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $this->filterIsNotEmpty($form)) {
            $searchedNameEmail = $form->getData()['nameEmail'];
            $searchedEvent = $form->getData()['event'];
            $searchedIsSubscribed = $form->getData()['isSubscribed'];

            if ($searchedEvent != "" ) {
                $selectMailingList = $em->createQueryBuilder('r')
                ->select('a')
                ->distinct('a.id')
                ->from('AdminBundle:Registration', 'r');
                $mailingList = $this->getSearchByEventQuery($selectMailingList, $searchedNameEmail, $searchedEvent, $searchedIsSubscribed)
                                    ->orderBy('a.id', 'ASC')
                                    ->setFirstResult(($page - 1) * $numItemsPerPage)
                                    ->setMaxResults($numItemsPerPage)
                                    ->getQuery()
                                    ->getResult();

                $selectCounPages = $em->createQueryBuilder('r')
                                        ->select('COUNT(DISTINCT a.id)')
                                        ->from('AdminBundle:Registration', 'r');
                $countPages = ceil(($this->getSearchByEventQuery($selectCounPages, $searchedNameEmail, $searchedEvent, $searchedIsSubscribed)
                                        ->getQuery()
                                        ->getSingleScalarResult())
                                        / $numItemsPerPage);
            } else {
                $selectMailingList = $em->createQueryBuilder()
                ->select('a')
                ->from('AdminBundle:Attendee', 'a');
                $mailingList = $this->getSearchByNameEmailQuery($selectMailingList, $searchedNameEmail, $searchedIsSubscribed)
                                    ->orderBy('a.id', 'ASC')
                                    ->setFirstResult(($page - 1) * $numItemsPerPage)
                                    ->setMaxResults($numItemsPerPage)
                                    ->getQuery()
                                    ->getResult();
                $selectCountPages = $em->createQueryBuilder()
                                        ->select('count(a.id)')
                                        ->from('AdminBundle:Attendee', 'a');
                $countPages = ceil(($this->getSearchByNameEmailQuery($selectCountPages, $searchedNameEmail, $searchedIsSubscribed)
                                        ->getQuery()
                                        ->getSingleScalarResult())
                                        / $numItemsPerPage);
            }
            
        } else {
            $repositoryAttendee = $this->getDoctrine()->getRepository(Attendee::class);
            $mailingList = $repositoryAttendee->findBy(array(), array(), $numItemsPerPage, ($page - 1) * $numItemsPerPage);
            $countPages = ceil(
                            ($em->createQueryBuilder()
                            ->select('count(a.id)')
                            ->from('AdminBundle:Attendee', 'a')
                            ->getQuery()
                            ->getSingleScalarResult()) 
                            / $numItemsPerPage);
        }

        return $this->render('AdminBundle:MailingList:index.html.twig', array(
            'form' => $form->createView(),
            'mailingList' => $mailingList,
            'currentPage' => $page,
            'countPages' => $countPages,
            'numItemsPerPage' => $numItemsPerPage,
        ));
    }

    private function filterIsNotEmpty($form) {
        return ($form->getData()['nameEmail'] != "")
                || ($form->getData()['event'] != "") 
                || ($form->getData()['isSubscribed'] != ""); 
    }

    private function getSearchByEventQuery($selectQuery, $nameEmail, $event, $isSubscribed) {
        $result = $selectQuery
            ->innerJoin('AdminBundle:Attendee', 'a', 'WITH', 'a.id = r.attendeeId')
            ->where('a.email LIKE :name OR a.firstName LIKE :name OR a.lastName LIKE :name')
            ->setParameter('name', '%'.$nameEmail.'%')
            ->innerJoin('AdminBundle:Event','e', 'WITH', 'e.id = r.eventDetailsId')
            ->innerJoin('AdminBundle:EventType', 'et', 'WITH', 'et.id = e.eventTypeId')
            ->andwhere('et.slug LIKE :event')
            ->setParameter('event', '%'.$event.'%');
        if ($isSubscribed == true) {
            $result -> andWhere('a.unsubscribed = :subsc')
            ->setParameter('subsc', !$isSubscribed);
        }
        return $result;
    }

    private function getSearchByNameEmailQuery($selectQuery, $nameEmail, $isSubscribed) {
        $result = $selectQuery
            ->where('a.email LIKE :name OR a.firstName LIKE :name OR a.lastName LIKE :name')
            ->setParameter('name', '%'.$nameEmail.'%');
        if ($isSubscribed == true) {
            $result -> andWhere('a.unsubscribed = :subsc')
            ->setParameter('subsc', !$isSubscribed);
        }
        return $result;
    }
}
