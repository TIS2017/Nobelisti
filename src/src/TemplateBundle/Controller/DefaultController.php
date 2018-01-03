<?php

namespace TemplateBundle\Controller;

use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    private static function getFallbackPath() {
        return DefaultController::buildTemplatePath('default');
    }

    private static function buildTemplatePath($templateName) {
        return '../templates/' . $templateName;
    }

    private static function trimPath($templatePath) {
        $prefix = "../templates";
        if (substr($templatePath, 0, strlen($prefix)) == $prefix) {
            $templatePath = substr($templatePath, strlen($prefix));
        }

        return '@Templates' . $templatePath;
    }

    public static function getTemplates() {
        return array_map(
            'basename',
            array_filter(glob(DefaultController::buildTemplatePath('*')), 'is_dir')
        );
    }

    public static function getTemplate($templateName, $file) {
        $templateNameOrDefault = self::getFilePath($templateName, 'index.html.twig');
        return self::trimPath($templateNameOrDefault);
    }

    private static function getFilePath($templateName, $file) {
        $filePath = DefaultController::buildTemplatePath($templateName) . '/' . $file;
        if(file_exists($filePath)){
            return $filePath;
        }

        $filePathFallback = DefaultController::getFallbackPath() . '/' . $file;
        if (file_exists($filePathFallback)){
            return $filePathFallback;
        }

        throw new Exception("Neither file nor fallback file does not exist");
    }

    /**
     * @Route("/tmp")
     */
    public function indexAction()
    {
        $templateName = $this->getTemplates()[1];

        $template = self::getTemplate($templateName, 'index.html.twig');


        return $this->render($template, array("data" => ""));
    }
}
