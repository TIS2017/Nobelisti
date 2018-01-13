<?php

namespace TemplateBundle\Twig;

use TemplateBundle\Controller\CustomTemplateController;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('extendable', array($this, 'extendableFilter')),
        );
    }

    public function extendableFilter($fileName, $eventType = 0)
    {
        return CustomTemplateController::getTemplate($eventType->getTemplate(), $fileName);
    }
}
