<?php

namespace AppBundle\Controller;

use EmailBundle\Controller\EmailController;
use AdminBundle\Entity\Registration;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ConfirmRegistrationController extends EmailController
{
    /**
     * @Route("/confirmation/{token}", name="confirm_registration")
     */
    public function indexAction($token)
    {
        $em = $this->getDoctrine()->getManager();
        $registration = $em->getRepository(Registration::class)->findOneBy(array('confirmationToken' => $token));

        if (null == $registration) {
            throw $this->createNotFoundException('Invalid registration token.');
        }

        $language = $registration->getLanguages();
        $eventType = $registration->getEvents()->getEventType();
        $context = ['event_type' => $eventType];
        $templateName = $eventType->getTemplate();

        $template = self::getTemplate($templateName, 'confirmation.html.twig');
        $languageContext = self::getLanguageFile($templateName, $language->getCode(), $context);

        $context['lang'] = $languageContext;

        if (null != $registration->getConfirmed()) {
            $this->addFlash('warning', $context['lang']['confirmation_already_confirmed']);
        } elseif (!$registration->canBeConfirmedNow()) {
            $this->addFlash('warning', $context['lang']['confirmation_expired']);
        } else {
            $date = new \DateTime('now');
            $registration->setConfirmed($date);
            $em->persist($registration);
            $em->flush();

            $this->addFlash('success', $context['lang']['confirmation_success']);
        }

        return $this->render($template, $context);
    }
}
