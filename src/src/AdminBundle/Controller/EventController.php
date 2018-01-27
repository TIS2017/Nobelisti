<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventLanguages;
use AdminBundle\Entity\EventOrganizers;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Language;
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
     * @Route("/event_type/edit/{id}/event/add", name="events_add", requirements={"id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function createAction($id, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(EventType::class);
        $eventType = $repository->findOneBy(['id' => $id]);

        $newEvent = new Event();
        $newEvent->setEventType($eventType);
        $form = $this->createForm(EventForm::class, $newEvent);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($newEvent);
            $em->flush();

            return $this->redirectToRoute('event_types_edit', ['id' => $id]);
        }

        return $this->render('AdminBundle:Event:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    private static $modalInputOrganizers = 'assign_organizer';

    private static $modalInputLanguages = 'assign_language';

    /**
     * @Route("/event_type/edit/{id}/event/{event_id}/edit", name="events_edit", requirements={"id"="\d+", "event_id"="\d+"})
     * @Method({"GET", "POST"})
     */
    public function editAction($id, $event_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        //EventOrganizers
        $eventOrganizers = $em->getRepository(EventOrganizers::class)->findBy(['event' => $event_id]);

        $eventOrganizersIds = [];
        foreach ($eventOrganizers as $eventOrganizer) {
            $eventOrganizersIds[] = $eventOrganizer->getOrganizer();
        }

        $organizers = $em->getRepository(Organizer::class)->findById($eventOrganizersIds);

        //EventLanguages
        $eventLanguages = $em->getRepository(EventLanguages::class)->findBy(['event' => $event_id]);

        $eventLanguagesIds = [];
        foreach ($eventLanguages as $eventLanguage) {
            $eventLanguagesIds[] = $eventLanguage->getLanguage();
        }

        $languages = $em->getRepository(Language::class)->findById($eventLanguagesIds);

        //Event
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $event = $repository->findOneBy(['id' => $event_id]);

        $form = $this->createForm(EventForm::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('event_types_edit', ['id' => $id]);
        }

        return $this->render('AdminBundle:Event:edit.html.twig', array(
            'form' => $form->createView(),
            'organizers' => $organizers,
            'languages' => $languages,
            'event_type_id' => $id,
            'modal_input_organizers' => self::$modalInputOrganizers,
            'modal_input_languages' => self::$modalInputLanguages,
            'event_id' => $event_id,
            'autocomplete_path_organizers' => 'autocomplete_organizers',
            'autocomplete_path_languages' => 'autocomplete_languages',
        ));
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/delete",
     *     name="events_delete",
     *     requirements={"id"="\d+", "event_id"="\d+"}
     * )
     * @Method("POST")
     */
    public function deleteAction($id, $event_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->findOneBy(array('id' => $event_id));

        if (!$event) {
            throw $this->createNotFoundException(
                'No event found for id '.$event_id
            );
        }

        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('event_types_edit', ['id' => $id]);
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/edit/unassign/organizer/{organizer_id}",
     *     name="events_unassign_organizer",
     *     requirements={"id"="\d+", "event_id"="\d+", "organizer_id"="\d+"}
     *)
     * @Method("POST")
     */
    public function unassignOrganizerAction($id, $event_id, $organizer_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eventOrganizer = $em->getRepository(EventOrganizers::class)->findOneBy(
            array(
                'event' => $event_id,
                'organizer' => $organizer_id,
            )
        );

        if ($eventOrganizer) {
            $em->remove($eventOrganizer);
            $em->flush();
        }

        return $this->redirectToRoute('events_edit', ['id' => $id, 'event_id' => $event_id]);
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/edit/unassign/language/{language_id}",
     *     name="events_unassign_language",
     *     requirements={"id"="\d+", "event_id"="\d+", "language_id"="\d+"}
     * )
     * @Method("POST")
     */
    public function unassignLanguageAction($id, $event_id, $language_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eventLanguage = $em->getRepository(EventLanguages::class)->findOneBy(
            array(
                'event' => $event_id,
                'language' => $language_id,
            )
        );

        if ($eventLanguage) {
            $em->remove($eventLanguage);
            $em->flush();
        }

        return $this->redirectToRoute('events_edit', ['id' => $id, 'event_id' => $event_id]);
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/edit/assign/organizer",
     *     name="events_assign_organizer",
     *     requirements={"id"="\d+", "event_id"="\d+"}
     * )
     * @Method("POST")
     */
    public function assignOrganizerAction($id, $event_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $organizer = $em->getRepository(Organizer::class)->findOneBy(
            ['email' => $request->get(self::$modalInputOrganizers)]
        );

        $event = $em->getRepository(Event::class)->findOneBy(['id' => $event_id]);

        $eventOrganizer = new EventOrganizers();
        $eventOrganizer->setEvent($event);
        $eventOrganizer->setOrganizer($organizer);

        $em->persist($eventOrganizer);
        $em->flush();

        return $this->redirectToRoute('events_edit', ['id' => $id, 'event_id' => $event_id]);
    }

    /**
     * @Route("event_type/edit/{id}/event/{event_id}/edit/assign/language",
     *     name="events_assign_language",
     *     requirements={"id"="\d+", "event_id"="\d+"}
     * )
     * @Method("POST")
     */
    public function assignLanguageAction($id, $event_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository(Language::class)->findOneBy(
            ['language' => $request->get(self::$modalInputLanguages)]
        );

        $event = $em->getRepository(Event::class)->findOneBy(['id' => $event_id]);

        $eventLanguage = new EventLanguages();
        $eventLanguage->setEvent($event);
        $eventLanguage->setLanguage($language);

        $em->persist($eventLanguage);
        $em->flush();

        return $this->redirectToRoute('events_edit', ['id' => $id, 'event_id' => $event_id]);
    }

    /**
     * @Route("/autocomplete/organizers", name="autocomplete_organizers")
     * @Method("GET")
     */
    public function autocompleteOrganizersAction(Request $request)
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

    /**
     * @Route("/autocomplete/languages", name="autocomplete_languages")
     * @Method("GET")
     */
    public function autocompleteLanguagesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $languages = $em->getRepository(Language::class)
            ->createQueryBuilder('l')
            ->where('l.language LIKE :language')
            ->setParameter('language', '%'.$request->get('term').'%')
            ->getQuery()
            ->getResult();

        $names = array();
        foreach ($languages as $language) {
            $names[] = $language->getLanguage();
        }

        $response = new JsonResponse();
        $response->setData($names);

        return $response;
    }
}
