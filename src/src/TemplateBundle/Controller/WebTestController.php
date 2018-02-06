<?php

namespace TemplateBundle\Controller;

use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Event;
use AdminBundle\Entity\Attendee;
use AppBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class WebTestController extends CustomTemplateController
{
    /**
     * @Route("/test/{eventSlug}/web", name="test_event")
     * @Method("GET")
     */
    public function testEventAction($eventSlug, Request $request)
    {
        $eventType = $this->getDoctrine()->getRepository(EventType::class)->findOneBy(
            ['slug' => $eventSlug]
        );

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for slug '.$slug
            );
        }

        $state = $request->query->get('state');
        $lang = $request->query->get('lang');

        $attendee = new Attendee();

        $form = $this->createForm(RegistrationForm::class, $attendee);
        $form->handleRequest($request);

        $context = [
            'event_type' => $eventType,
            'form' => $form->createView(),
            'state' => $state,
        ];

        $templateName = $eventType->getTemplate();

        $template = self::getTemplate($templateName, 'index.html.twig');
        $languageContext = self::getLanguageFile($templateName, $lang, $context);

        $context['lang'] = $languageContext;

        if ($form->isSubmitted() && $form->isValid()) {
            $context['attendee'] = $attendee;
            // todo: show success page
        }

        return $this->render($template, $context);
    }

    /**
     * @Route("/test/{eventTypeId}/languages", name="test_event_languages", requirements={"eventTypeId"="\d+"})
     * @Method("GET")
     */
    public function testEventLanguagesAction($eventTypeId, Request $request)
    {
        $eventType = $this->getDoctrine()->getRepository(EventType::class)->findOneBy(
            ['id' => $eventTypeId]
        );

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for id '.$eventTypeId
            );
        }

        $getNamesFromLanguage = function ($language) {
            return $language->getLanguage()->getCode();
        };

        $availableEventTypeLanguages = self::getLanguageNames($eventType->getTemplate());
        $availableEventTypeLanguages = array_map(function ($l) { return substr($l, 0, -10); }, $availableEventTypeLanguages);
        $definedEventTypeLanguages = array_map($getNamesFromLanguage, $eventType->getLanguages()->toArray());

        $eventId = $request->query->get('eventId');
        if (null != $eventId) {
            $event = $this->getDoctrine()->getRepository(Event::class)->findOneBy(
                ['id' => $eventId]
            );

            if (!$event) {
                throw $this->createNotFoundException(
                    'No event found for id '.$eventId
                );
            }

            $allAvailableLanguages = $availableEventTypeLanguages;
            if (!empty($event->getTemplateOverride())) {
                $availableEventLanguages = self::getLanguageNames($event->getTemplateOverride());
                $availableEventLanguages = array_map(function ($l) { return substr($l, 0, -10); }, $availableEventLanguages);
                $allAvailableLanguages = array_unique(array_merge($availableEventTypeLanguages, $availableEventLanguages));
            }

            $allDefinedLanguages = $definedEventTypeLanguages;
            if (!empty($event->getLanguages())) {
                $definedEventLanguages = array_map($getNamesFromLanguage, $event->getLanguages()->toArray());
                $allDefinedLanguages = array_unique(array_merge($definedEventTypeLanguages, $definedEventLanguages));
            }

            $notFoundLanguages = array();
            foreach ($allDefinedLanguages as $definedLanguage) {
                if (!in_array($definedLanguage, $allAvailableLanguages)) {
                    $notFoundLanguages[] = $definedLanguage;
                }
            }

            return $this->redirectToRoute('events_edit', ['id' => $eventType->getId(), 'event_id' => $event->getId(), 'notFoundLanguages' => $notFoundLanguages]);
        } else {
            $notFoundLanguages = array();
            foreach ($definedEventTypeLanguages as $definedLanguage) {
                if (!in_array($definedLanguage, $availableEventTypeLanguages)) {
                    $notFoundLanguages[] = $definedLanguage;
                }
            }

            return $this->redirectToRoute('event_types_edit', ['id' => $eventType->getId(), 'notFoundLanguages' => $notFoundLanguages]);
        }
    }
}
