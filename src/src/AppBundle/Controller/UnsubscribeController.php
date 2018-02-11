<?php

namespace AppBundle\Controller;

use EmailBundle\Controller\EmailController;
use AdminBundle\Entity\Registration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UnsubscribeController extends EmailController
{
    /**
     * @Route("/unsubscribe/{token}", name="unsubscribe_mailinglist")
     */
    public function indexAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $registration = $em->getRepository(Registration::class)->findOneBy(array('confirmationToken' => $token));

        if (null == $registration) {
            throw $this->createNotFoundException('Invalid registration token.');
        }

        $attendee = $registration->getAttendee();
        $language = $registration->getLanguages();
        $eventType = $registration->getEvents()->getEventType();
        $event = $registration->getEvents();
        $context = [
            'event_type' => $eventType,
            'event' => $event
        ];
        $templateName = $eventType->getTemplate();
        $languageContext = self::getLanguageFile($templateName, $language->getCode(), $context);
        $context['lang'] = $languageContext;
        $template = self::getTemplate($templateName, 'unsubscribe.html.twig');

        if ($attendee->getUnsubscribed()) {
            $this->addFlash('warning', $context['lang']['already_unsubscribed']);
        } else {
            $attendee->setUnsubscribed(true);
            $em->persist($attendee);
            $em->flush();

            $this->addFlash('success', $context['lang']['successfuly_unsubscribed']);
        }

        return $this->render($template, $context);
    }
}
