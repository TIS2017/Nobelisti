<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\OrganizerForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Organizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrganizerController extends Controller
{
    /**
     * @Route("/organizers", name="organizers")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $defaultData = array('email' => '');
        $form = $this->createFormBuilder($defaultData)
            ->add('email', TextType::class)
            ->add('filter', SubmitType::class, array('label' => 'Filter'))
            ->setMethod('GET')
            ->getForm();

        $form->handleRequest($request);

        $organizers = [];
        $repository = $this->getDoctrine()->getRepository(Organizer::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchedEmail = $form->getData()['email'];

            $em = $this->getDoctrine()->getManager();
            $organizers = $em->getRepository(Organizer::class)
                ->createQueryBuilder('o')
                ->where('o.email LIKE :email')
                ->setParameter('email', '%'.$searchedEmail.'%')
                ->getQuery()
                ->getResult();
        } else {
            $organizers = $repository->findAll();
        }

        return $this->render('AdminBundle:Organizer:index.html.twig', array(
            'form' => $form->createView(),
            'organizers' => $organizers,
        ));
    }

    /**
     * @Route("/organizers/create", name="organizers_add")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $newOrganizer = new Organizer();
        $form = $this->createForm(OrganizerForm::class, $newOrganizer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newOrganizer);
            $em->flush();

            return $this->redirectToRoute('organizers');
        }

        return $this->render('AdminBundle:Organizer:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/organizers/edit/{id}", name="organizers_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $organizer = $em->getRepository(Organizer::class)->findOneBy(array('id' => $id));

        if (!$organizer) {
            throw $this->createNotFoundException(
                'No organizer found for id '.$id
            );
        }

        $form = $this->createForm(OrganizerForm::class, $organizer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('organizers');
        }

        return $this->render('AdminBundle:Organizer:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/organizers/delete/{id}", name="organizers_delete", requirements={"id"="\d+"})
     * @Method("POST")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $organizer = $em->getRepository(Organizer::class)->findOneBy(array('id' => $id));

        if (!$organizer) {
            throw $this->createNotFoundException(
                'No organizer found for id '.$id
            );
        }

        $em->remove($organizer);
        $em->flush();

        return $this->redirectToRoute('organizers');
    }
}
