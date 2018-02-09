<?php

namespace AdminBundle\Controller;

use AdminBundle\Form\OrganizerFilterForm;
use AdminBundle\Form\OrganizerForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AdminBundle\Entity\Organizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class OrganizerController extends Controller
{
    /**
     * @Route("/organizers", name="organizers")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(OrganizerFilterForm::class, array('email' => ''));
        $form->handleRequest($request);

        $repository = $this->getDoctrine()->getRepository(Organizer::class);

        if ($form->isSubmitted() && $form->isValid()) {
            $searchedEmail = $form->getData()['email'];
            $organizers = $repository->getOrganizersByEmail($searchedEmail);
        } else {
            $organizers = $repository->findAll();
        }

        return $this->render('AdminBundle:Organizer:index.html.twig', array(
            'form' => $form->createView(),
            'organizers' => $organizers,
        ));
    }

    /**
     * @Route("/organizers/add", name="organizers_add")
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
     * @Route("/organizers/edit/{id}", name="organizers_edit", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $organizer = $em->find(Organizer::class, $id);

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
        $organizer = $em->find(Organizer::class, $id);

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
