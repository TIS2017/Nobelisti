<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\EventType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use TemplateBundle\Controller\CustomTemplateController;

class DefaultController extends CustomTemplateController
{
    /**
     * @Route("/{slug}", name="frontend_index")
     */
    public function indexAction($slug)
    {
        $eventType = $this->getDoctrine()->getRepository(EventType::class)->findOneBy(
            ['slug' => $slug]
        );

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for slug ' . $slug
            );
        }

        $template = self::getTemplate($eventType->getTemplate(), 'index.html.twig');

        return $this->render($template, array("data" => ""));
    }
}
