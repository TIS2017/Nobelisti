<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\EventTypeLanguages;
use AdminBundle\Entity\Language;
use AdminBundle\Form\EventTypeForm;
use AdminBundle\Form\EmailTestForm;
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

    private static $modalInputLanguages = 'assign_language';

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

        $languages = $eventType->getLanguages();

        $form = $this->createForm(EventTypeForm::class, $eventType, ['read_only_slug' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('event_types');
        }

        $testUrls = array();
        foreach ($languages as $language) {
            $languageCode = $language->getLanguage()->getCode();
            $basicParameters = array('eventSlug' => $eventType->getSlug(), 'lang' => $language->getLanguage()->getCode());
            $testUrls[] = [
                'url' => $this->generateUrl('test_event', array_merge(array('state' => 'registration_not_started'), $basicParameters)),
                'label' => $languageCode.' - Registraion not started',
            ];
            $testUrls[] = [
                'url' => $this->generateUrl('test_event', array_merge(array('state' => 'registration_open'), $basicParameters)),
                'label' => $languageCode.' - Registraion open',
            ];
            $testUrls[] = [
                'url' => $this->generateUrl('test_event', array_merge(array('state' => 'registration_finished'), $basicParameters)),
                'label' => $languageCode.' - Registraion finished',
            ];
            $testUrls[] = [
                'url' => $this->generateUrl('test_event', array_merge(array('state' => 'registration_no_capacity'), $basicParameters)),
                'label' => $languageCode.' - Registraion no capacity',
            ];
        }

        $emailTestForm = $this->createForm(EmailTestForm::class);

        $languageNames = array_map(function ($l) { return $l->getLanguage(); }, $languages->toArray());

        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findBy(['eventType' => $id]);
        $notFoundLanguages = $request->query->get('notFoundLanguages');

        return $this->render('AdminBundle:EventType:edit.html.twig', array(
            'form' => $form->createView(),
            'testUrls' => $testUrls,
            'emailTestForm' => $emailTestForm->createView(),
            'languages' => $languageNames,
            'notFoundLanguages' => $notFoundLanguages,
            'modal_input_languages' => self::$modalInputLanguages,
            'event_type_id' => $id,
            'events' => $events,
            'autocomplete_path_languages' => 'autocomplete_languages',
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

    /**
     * @Route("event_type/edit/{id}/assign/language",
     *     name="event_types_assign_language",
     *     requirements={"id"="\d+"}
     * )
     * @Method("POST")
     */
    public function assignLanguageAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $language = $em->getRepository(Language::class)->findOneBy(
            ['language' => $request->get(self::$modalInputLanguages)]
        );

        $eventType = $em->find(EventType::class, $id);

        $eventTypeLanguage = new EventTypeLanguages();
        $eventTypeLanguage->setEventType($eventType);
        $eventTypeLanguage->setLanguage($language);

        $this->tryToAssign($em, $eventTypeLanguage, 'Language');

        return $this->redirectToRoute('event_types_edit', ['id' => $id]);
    }

    public function tryToAssign($em, $object, $text)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($object);
        if (0 == count($errors)) {
            try {
                $em->persist($object);
                $em->flush();
                $this->addFlash('success', $text.' assigned!');
            } catch (\Doctrine\DBAL\DBALException $e) {
                $this->addFlash('danger', $text.' could not be assigned!');
            }
        } else {
            $this->addFlash('warning', $text.' already assigned!');
        }
    }

    /**
     * @Route("event_type/edit/{id}/unassign/language/{language_id}",
     *     name="event_types_unassign_language",
     *     requirements={"id"="\d+", "language_id"="\d+"}
     * )
     * @Method("POST")
     */
    public function unassignLanguageAction($id, $language_id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $eventLanguage = $em->getRepository(EventTypeLanguages::class)->findOneBy(
            array(
                'eventType' => $id,
                'language' => $language_id,
            )
        );

        if ($eventLanguage) {
            $em->remove($eventLanguage);
            $em->flush();
        }

        return $this->redirectToRoute('event_types_edit', ['id' => $id]);
    }
}
