<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventType;
use AdminBundle\Form\EventTypeForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class EventTypeController extends Controller
{
    /**
     * @Route("/", name="event_types")
     * @Method("GET")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository(EventType::class);
        $eventTypes = $repository->findAll();

        return $this->render('AdminBundle:EventType:index.html.twig', array(
            'eventTypes' => $eventTypes,
        ));
    }

    /**
     * @Route("/event_type/add", name="event_types_add")
     * @Method({"GET", "POST"})
     */
    public function createAction(Request $request)
    {
        $newEventType = new EventType();
        $form = $this->createForm(EventTypeForm::class, $newEventType);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newEventType);
            $em->flush();

            return $this->redirectToRoute('event_types');
        }

        return $this->render('AdminBundle:EventType:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/event_type/edit/{id}", name="event_types_edit", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $eventType = $em->getRepository(EventType::class)->findOneBy(array('id' => $id));

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for id '.$id
            );
        }

        $form = $this->createForm(EventTypeForm::class, $eventType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('event_types');
        }

        $testUrls = array();
        foreach ($eventType->getLanguages() as $language) {
            $basicParameters = array('eventSlug' => $eventType->getSlug(), 'lang' => $language->getCode());
            $testUrls[] = $this->generateUrl('event_test', array_merge(array('state' => 'registration_not_started'), $basicParameters);
            $testUrls[] = $this->generateUrl('event_test', array_merge(array('state' => 'registration_open'), $basicParameters);
            $testUrls[] = $this->generateUrl('event_test', array_merge(array('state' => 'registration_finished'), $basicParameters);
            $testUrls[] = $this->generateUrl('event_test', array_merge(array('state' => 'registration_no_capacity'), $basicParameters);
        }

        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findBy(['eventTypeId' => $id]);

        return $this->render('AdminBundle:EventType:edit.html.twig', array(
            'form' => $form->createView(),
            'testUrls' => $testUrls,
            'events' => $events,
        ));
    }

    /**
     * @Route("/event_type/delete/{id}", name="event_types_delete", requirements={"id"="\d+"})
     * @Method("POST")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $eventType = $em->getRepository(EventType::class)->findOneBy(array('id' => $id));

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for id '.$id
            );
        }

        $em->remove($eventType);
        $em->flush();

        return $this->redirectToRoute('event_types');
    }
}
