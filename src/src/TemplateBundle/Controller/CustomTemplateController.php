<?php

namespace TemplateBundle\Controller;

use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CustomTemplateController extends Controller
{

    private static function getFallbackPath() {
        return self::buildTemplatePath('default');
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

    public static function getTemplateNames() {
        return array_map(
            'basename',
            array_filter(glob(self::buildTemplatePath('*')), 'is_dir')
        );
    }

    public static function getTemplateNamesForForm() {
        $templateNames = self::getTemplateNames();
        return array_combine($templateNames, $templateNames);
    }

    public static function getTemplate($templateName, $file) {
        $templateNameOrDefault = self::getFilePath($templateName, $file);
        return self::trimPath($templateNameOrDefault);
    }

    private static function getFilePath($templateName, $file) {
        $filePath = self::buildTemplatePath($templateName) . '/' . $file;
        if(file_exists($filePath)){
            return $filePath;
        }

        $filePathFallback = self::getFallbackPath() . '/' . $file;
        if (file_exists($filePathFallback)){
            return $filePathFallback;
        }

        throw new Exception("Neither file nor fallback file does not exist");
    }
}
