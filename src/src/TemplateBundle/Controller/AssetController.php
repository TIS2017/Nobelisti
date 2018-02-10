<?php

namespace TemplateBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AssetController extends CustomTemplateController
{
    /**
     * @Route("/asset/{templateName}/css/{assetName}", name="get_css", defaults={"assetName": "styles"})
     * @Method("GET")
     */
    public function getCssAction($templateName, $assetName, Request $request)
    {
        if (empty($assetName)) {
            $assetName = 'styles';
        } elseif (false !== strpos($assetName, '..') || false !== strpos($assetName, '/')) {
            throw new \Exception('Forbidden characters used as a asset name.');
        }

        $stylePath = self::getFilePath($templateName, 'css/'.$assetName.'.css');

        $response = new BinaryFileResponse($stylePath);
        $response->headers->set('Content-Type', 'text/css');

        return $response;
    }

    /**
     * @Route("/asset/{templateName}/js/{assetName}", name="get_js", defaults={"assetName": "script"})
     * @Method("GET")
     */
    public function getJsAction($templateName, $assetName, Request $request)
    {
        if (empty($assetName)) {
            $assetName = 'script';
        } elseif (false !== strpos($assetName, '..') || false !== strpos($assetName, '/')) {
            throw new \Exception('Forbidden characters used as a asset name.');
        }

        $scriptPath = self::getFilePath($templateName, 'scripts/'.$assetName.'.js');

        $response = new BinaryFileResponse($scriptPath);
        $response->headers->set('Content-Type', 'application/javascript');

        return $response;
    }
}
