<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Event;
use AdminBundle\Form\EventForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/event/add", name="event_add")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $newEvent = new Event();
        $form = $this->createForm(EventForm::class, $newEvent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newEvent);
            $em->flush();

            return $this->redirectToRoute('event_type_edit');
        }

        return $this->render('AdminBundle:Event:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event/edit/{id}", name="event_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction()
    {
        return $this->render('AdminBundle:Event:index.html.twig');
    }

    /**
     * @Route("/event/delete/{id}", name="event_delete")
     * @Method("POST")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->findOneBy(array('id' => $id));

        if (!$event) {
            throw $this->createNotFoundException(
                'No event type found for id '.$id
            );
        }

        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('event_type_edit');
    }
}
