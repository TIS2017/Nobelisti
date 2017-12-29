<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventType;
use AdminBundle\Form\EventForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class EventController extends Controller
{
    /**
     * @Route("/event_type/edit/{id}/event/add", name="event_add")
     * @Method({"GET", "POST"})
     */
    public function createAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(EventType::class);
        $eventType = $repository->findOneBy(['id' => intval($id)]);

        $newEvent = new Event();
        $newEvent->setEventTypeId($eventType);
        $form = $this->createForm(EventForm::class, $newEvent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newEvent);
            $em->flush();

            return $this->redirectToRoute('event_type_edit', ['id' => $id]);
        }

        return $this->render('AdminBundle:Event:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("event_type/edit/{id}/event/edit/{event_id}", name="event_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, $event_id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $event = $repository->findOneBy(['id' => intval($event_id)]);

        $form = $this->createForm(EventForm::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            return $this->redirectToRoute('event_type_edit', ['id' => $id]);
        }

        return $this->render('AdminBundle:Event:edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("event_type/edit/{id}/event/delete/{event_id}", name="event_delete")
     * @Method("POST")
     */
    public function deleteAction($id, $event_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->findOneBy(array('id' => intval($event_id)));

        if (!$event) {
            throw $this->createNotFoundException(
                'No event found for id '.$event_id
            );
        }

        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('event_type_edit', ['id' => $id]);
    }
}
