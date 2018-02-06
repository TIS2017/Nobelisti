<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Attendee;
use AppBundle\Form\RegistrationForm;
use EmailBundle\Controller\EmailController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends EmailController
{
    /**
     * @Route("/{slug}/{_locale}", name="frontend_index", defaults={"_locale": "DEFAULT"})
     * @Method({"GET", "POST"})
     */
    public function indexAction($slug, $_locale, Request $request)
    {
        $eventType = $this->getDoctrine()->getRepository(EventType::class)->findOneBy(
            ['slug' => $slug]
        );

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for slug '.$slug
            );
        }

        if ('DEFAULT' === $_locale) {
            $language = 'sk_SK'; // todo, select a default language or something
        } else {
            $language = $_locale;
            // todo: check language is enabled for this $eventType
        }

        $attendee = new Attendee();

        $form = $this->createForm(RegistrationForm::class, $attendee);
        $form->handleRequest($request);

        $context = [
            'event_type' => $eventType,
            'form' => $form->createView(),
        ];

        $templateName = $eventType->getTemplate();

        $template = self::getTemplate($templateName, 'index.html.twig');
        $languageContext = self::getLanguageFile($templateName, $language, $context);

        $context['lang'] = $languageContext;
        $context['lang_code'] = $language;

        if ($form->isSubmitted() && $form->isValid()) {
            $context['attendee'] = $attendee;

            $this->sendEmail($attendee, $context, $templateName, 'registration');

            // todo: show success page or something
        }

        return $this->render($template, $context);
    }
}
