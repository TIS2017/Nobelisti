<?php

namespace TemplateBundle\Controller;

use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Event;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Registration;
use AppBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class WebTestController extends CustomTemplateController
{
    /**
     * @Route("/test/{eventSlug}/web", name="test_event")
     * @Method({"GET", "POST"})
     */
    public function testEventAction($eventSlug, Request $request)
    {
        $eventType = $this->getDoctrine()->getRepository(EventType::class)->findOneBy(
            ['slug' => $eventSlug]
        );

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for slug '.$eventSlug
            );
        }

        $state = $request->query->get('state');
        $lang = $request->query->get('lang');

        $attendee = new Attendee();

        $em = $this->getDoctrine()->getManager();

        $registration = new Registration();
        $registration->generateConfirmationToken();
        $registration->setLanguage($lang);

        $context = ['event_type' => $eventType];
        $templateName = $eventType->getTemplate();

        $languageContext = self::getLanguageFile($templateName, $lang, $context);

        $context['lang'] = $languageContext;
        $context['lang_code'] = $lang;

        if ('registration_open' == $state || 'registration_no_capacity' == $state) {
            $form = $this->getEmptyRegistraionForm($eventType, $context['lang']);
            $form->handleRequest($request);

            $context['form'] = $form->createView();
            $template = self::getTemplate($templateName, 'index.html.twig');

            if ($form->isSubmitted() && $form->isValid()) {
                $email = $form->getData()['email'];
                $firstName = $form->getData()['first_name'];
                $lastName = $form->getData()['last_name'];
                $event = $em->getRepository(Event::class)->find($form->getData()['event_choice']);
                $unsubscribed = !$form->getData()['subscribed'];

                $registration->setEvent($event);

                if ('registration_no_capacity' == $state) {
                    $this->addFlash('error', $context['lang']['capacity_full']);

                    return $this->render($template, $context);
                }

                if ('registration_open' == $state) {
                    $attendee->setEmail($email);
                    $attendee->setFirstName($firstName);
                    $attendee->setlastName($lastName);
                    $attendee->setLanguage($lang);
                    $attendee->setUnsubscribed($unsubscribed);
                    $registration->setAttendee($attendee);
                    $repositoryRegistration = $this->getDoctrine()->getRepository(Registration::class);
                    $code = $repositoryRegistration->generateCodeForEvent($event->getId());
                    $registration->setCode($code);

                    $context['attendee'] = $attendee;
                    $context['registration'] = $registration;

                    $this->addFlash('success', $context['lang']['registration_success']);
                    $context['form'] = $this->getEmptyRegistraionForm($eventType, $context['lang'])->createView();
                }
            }

            return $this->render($template, $context);
        } elseif ('registration_not_open' == $state) {
            $template = self::getTemplate($templateName, 'registration_not_opened.html.twig');

            return $this->render($template, $context);
        }

        throw $this->createNotFoundException();
    }

    /**
     * @Route("/test/{eventTypeId}/languages", name="test_event_languages", requirements={"eventTypeId"="\d+"})
     * @Method("GET")
     */
    public function testEventLanguagesAction($eventTypeId, Request $request)
    {
        $repo = $this->getDoctrine()->getManager();
        $eventType = $repo->find(EventType::class, $eventTypeId);

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
            $event = $repo->find(Event::class, $eventId);

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

            $this->showNotFoundLanguagesFlash($notFoundLanguages);

            return $this->redirectToRoute('events_edit', ['id' => $eventType->getId(), 'event_id' => $event->getId()]);
        } else {
            $notFoundLanguages = array();
            foreach ($definedEventTypeLanguages as $definedLanguage) {
                if (!in_array($definedLanguage, $availableEventTypeLanguages)) {
                    $notFoundLanguages[] = $definedLanguage;
                }
            }

            $this->showNotFoundLanguagesFlash($notFoundLanguages);

            return $this->redirectToRoute('event_types_edit', ['id' => $eventType->getId()]);
        }
    }

    private function showNotFoundLanguagesFlash($notFoundLanguages)
    {
        if (empty($notFoundLanguages)) {
            $this->addFlash('success', 'All languages are set correctly.');
        } else {
            $message = 'The following languages are missing: '.join(', ', $notFoundLanguages);
            $this->addFlash('danger', $message);
        }
    }

    private function getEmptyRegistraionForm($eventType, $contextLang)
    {
        $events = $eventType->getEvents();
        $eventOptions = [];
        foreach ($events as $event) {
            $eventOptions[$event->getAddress()] = $event->getId();
        }

        $defaultData = array(
            'first_name' => '',
            'last_name' => '',
            'email' => '',
            'events' => $eventOptions,
            'lang' => $contextLang
        );

        return $this->createForm(RegistrationForm::class, $defaultData);
    }
}
