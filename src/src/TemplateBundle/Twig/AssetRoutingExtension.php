<?php

namespace TemplateBundle\Twig;

use Symfony\Bridge\Twig\Extension\RoutingExtension;

class AssetRoutingExtension extends RoutingExtension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('getCSS', array($this, 'getCSS'), array('is_safe' => array('html'))),
            new \Twig_SimpleFunction('getJS', array($this, 'getJS'), array('is_safe' => array('html'))),
        );
    }

    public function getCSS($templateName, $assetName)
    {
        $cssPath = $this->getPath('get_css', array('templateName' => $templateName, 'assetName' => $assetName), true);

        return '<link href="'.$cssPath.'" rel="stylesheet" />';
    }

    public function getJS($templateName, $assetName)
    {
        $jsPath = $this->getPath('get_js', array('templateName' => $templateName, 'assetName' => $assetName), true);

        return '<script type="text/javascript" src="'.$jsPath.'"></script>';
    }
}
