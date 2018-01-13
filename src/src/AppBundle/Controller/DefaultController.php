<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TemplateBundle\Controller\CustomTemplateController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends CustomTemplateController
{
    /**
     * @Route("/{slug}/{_locale}", name="frontend_index", defaults={"_locale": "DEFAULT"})
     * @Method("GET")
     */
    public function indexAction($slug, $_locale)
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

        $context = [
            'event_type' => $eventType,
        ];

        $templateName = $eventType->getTemplate();

        $template = self::getTemplate($templateName, 'index.html.twig');
        $languageContext = self::getLanguageFile($templateName, $language, $context);

        $context['lang'] = $languageContext;

        return $this->render($template, $context);
    }
}
