<?php

namespace TemplateBundle\Controller;

use PHPUnit\Runner\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;

class CustomTemplateController extends Controller
{
    private static function getFallbackPath()
    {
        return self::buildTemplatePath('default');
    }

    private static function buildTemplatePath($templateName)
    {
        return '../templates/'.$templateName;
    }

    private static function trimPath($templatePath)
    {
        $prefix = '../templates';
        if (substr($templatePath, 0, strlen($prefix)) == $prefix) {
            $templatePath = substr($templatePath, strlen($prefix));
        }

        return '@Templates'.$templatePath;
    }

    public static function getTemplateNames()
    {
        return array_map(
            'basename',
            array_filter(glob(self::buildTemplatePath('*')), 'is_dir')
        );
    }

    public static function getTemplateNamesForForm()
    {
        $templateNames = self::getTemplateNames();

        return array_combine($templateNames, $templateNames);
    }

    public static function getTemplate($templateName, $file)
    {
        $templateNameOrDefault = self::getFilePath($templateName, $file);

        return self::trimPath($templateNameOrDefault);
    }

    protected static function getFilePath($templateName, $file)
    {
        $filePath = self::buildTemplatePath($templateName).'/'.$file;
        if (file_exists($filePath)) {
            return $filePath;
        }

        $filePathFallback = self::getFallbackPath().'/'.$file;
        if (file_exists($filePathFallback)) {
            return $filePathFallback;
        }

        throw new Exception('Neither file nor fallback file does not exist');
    }

    private static function buildLanguagePath($templateName, $languageName)
    {
        return '../templates/'.$templateName.'/languages/'.$languageName.'.yaml.twig';
    }

    public static function getLanguageNames($templateName)
    {
        $languagesPath = self::buildLanguagePath($templateName, '*');

        return array_map('basename', glob($languagesPath));
    }

    protected function getFileToYaml($path, $context)
    {
        $rawContent = file_get_contents($path);

        $template = $this->get('twig')->createTemplate($rawContent);
        $rawYaml = $template->render($context);

        return Yaml::parse($rawYaml);
    }

    public function getLanguageFile($templateName, $language, $context = [])
    {
        $languagesPath = self::buildLanguagePath($templateName, $language);

        return $this->getFileToYaml($languagesPath, $context);
    }
}
