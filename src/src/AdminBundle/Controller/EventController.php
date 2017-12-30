<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventOrganizers;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Organizer;
use AdminBundle\Form\EventForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * @Route("event_type/edit/{id}/event/{event_id}/edit", name="event_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction($id, $event_id, Request $request)
    {
        $em = $this->getDoctrine();
        $eventOrganizers = $em->getRepository(EventOrganizers::class)->findBy(['eventId' => $event_id]);

        $eventOrganizers_ids = [];
        foreach ($eventOrganizers as $eventOrganizer) {
            $eventOrganizers_ids[] = $eventOrganizer->getOrganizerId();
        }

        $organizers = $em->getRepository(Organizer::class)->findById($eventOrganizers_ids);

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
            'organizers' => $organizers,
            'event_type_id' => $id,
        ));
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/delete", name="event_delete")
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

        return $this->redirectToRoute('event_edit', ['id' => $id, 'event_id' => $event_id]);
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/edit/unassign/organizer/{organizer_id}", name="event_unassign_organizer")
     * @Method("POST")
     */
    public function unassignOrganizerAction($id, $event_id, $organizer_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eventOrganizer = $em->getRepository(EventOrganizers::class)->findOneBy(
            array(
                'eventId' => $event_id,
                'organizerId' => $organizer_id,
            )
        );

        if (!$eventOrganizer) {
            throw $this->createNotFoundException(
                'No organizer found for id '.$organizer_id.$event_id
            );
        }

        $em->remove($eventOrganizer);
        $em->flush();

        return $this->redirectToRoute('event_edit', ['id' => $id, 'event_id' => $event_id]);
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/edit/assign/organizer", name="event_assign_organizer")
     * @Method("POST")
     */
    public function assignOrganizerAction($id, $event_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //todo zabezpecit, aby nenasiel deleted organizera
        $organizer = $em->getRepository(Organizer::class)->findOneBy(['email' => $request->get('birds')]);

        $event = $em->getRepository(Event::class)->findOneBy(['id' => $event_id]);

        $eventOrganizer = new EventOrganizers();
        $eventOrganizer->setEventId($event);
        $eventOrganizer->setOrganizerId($organizer);

        $em->persist($eventOrganizer);
        $em->flush();

        return $this->redirectToRoute('event_edit', ['id' => $id, 'event_id' => $event_id]);
    }

    /**
     * @Route("/autocomplete", name="autocomplete")
     */
    public function autocompleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $organizers = $em->getRepository(Organizer::class)
            ->createQueryBuilder('o')
            ->where('o.email LIKE :email')
            ->setParameter('email', '%'.$request->get('term').'%')
            ->getQuery()
            ->getResult();

        $emails = array();
        foreach ($organizers as $organizer) {
            $emails[] = $organizer->getEmail();
        }

        $response = new JsonResponse();
        $response->setData($emails);

        return $response;
    }
}
