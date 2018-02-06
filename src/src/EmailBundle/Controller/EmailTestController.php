<?php

namespace EmailBundle\Controller;

use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Language;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class EmailTestController extends EmailController
{
    /**
     * @Route("/test/{eventSlug}/email/new", name="test_new_event_mail")
     * @Method("POST")
     */
    public function testNewEventMailAction($eventSlug, Request $request)
    {
        $eventType = $this->getDoctrine()->getRepository(EventType::class)->findOneBy(
            ['slug' => $eventSlug]
        );

        $attendee = self::createMockAttendee($request->request);
        $templateName = $eventType->getTemplate();

        $context = [
            'event_type' => $eventType,
        ];

        $languageCode = $attendee->getLanguages()->getCode();
        $languageContext = self::getLanguageFile($templateName, $languageCode, $context);
        $context['lang'] = $languageContext;
        $context['lang_code'] = $languageCode;
        $context['attendee'] = $attendee;

        $this->sendEmail($attendee, $context, $templateName, 'new_event');

        return $this->redirectToRoute('event_types_edit', ['id' => $eventType->getId()]);
    }

    /**
     * @Route("/test/{eventId}/email/registration", name="test_registration_mail")
     * @Method("POST")
     */
    public function testRegistrationMailAction($eventId, Request $request)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);
        $eventType = $event->getEventType();

        $attendee = self::createMockAttendee($request->request);
        $templateName = self::getEventTemplateName($event);

        $context = [
            'event' => $event,
            'event_type' => $eventType,
        ];

        $languageCode = $attendee->getLanguages()->getCode();
        $languageContext = self::getLanguageFile($templateName, $languageCode, $context);
        $context['lang'] = $languageContext;
        $context['lang_code'] = $languageCode;
        $context['attendee'] = $attendee;

        $this->sendEmail($attendee, $context, $templateName, 'registration');

        return $this->redirectToRoute('events_edit', ['id' => $eventType->getId(), 'event_id' => $event->getId()]);
    }

    /**
     * @Route("/test/{eventId}/email/reminder", name="test_reminder_mail")
     * @Method("POST")
     */
    public function testReminderMailAction($eventId, Request $request)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($eventId);
        $eventType = $event->getEventType();

        $attendee = self::createMockAttendee($request->request);
        $templateName = self::getEventTemplateName($event);

        $context = [
            'event' => $event,
            'event_type' => $eventType,
        ];

        $languageCode = $attendee->getLanguages()->getCode();
        $languageContext = self::getLanguageFile($templateName, $languageCode, $context);
        $context['lang'] = $languageContext;
        $context['lang_code'] = $languageCode;
        $context['attendee'] = $attendee;

        $this->sendEmail($attendee, $context, $templateName, 'reminder');

        return $this->redirectToRoute('events_edit', ['id' => $eventType->getId(), 'event_id' => $event->getId()]);
    }

    private function createMockAttendee($parameterBag)
    {
        $email = $parameterBag->get('email');
        $firstName = $parameterBag->get('first_name');
        $lastName = $parameterBag->get('last_name');
        $languageCode = $parameterBag->get('language');

        $language = $this->getDoctrine()->getRepository(Language::class)->findOneBy(
            ['code' => $languageCode]
        );

        $attendee = new Attendee();
        $attendee->setEmail($email);
        $attendee->setFirstName($firstName);
        $attendee->setLastName($lastName);
        $attendee->setLanguages($language);

        return $attendee;
    }

    private function getEventTemplateName($event)
    {
        $templateOverride = $event->getTemplateOverride();

        if (empty($templateOverride)) {
            return $event->getEventType()->getTemplate();
        }

        return $templateOverride;
    }
}
