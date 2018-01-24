<?php

namespace EmailBundle\Controller;

use AppBundle\Entity\Attendee;
use EmailBundle\Entity\EmailLog;
use TemplateBundle\Controller\CustomTemplateController;

class EmailController extends CustomTemplateController
{
    protected function renderToString($templateName, $emailPath, $context = [])
    {
        $templatePlain = self::getTemplate($templateName, $emailPath);

        return $this->renderView($templatePlain, $context);
    }

    protected function getEmailMeta($templateName, $emailPath, $context = [])
    {
        $emailPath = self::getFilePath($templateName, $emailPath);

        return $this->getArrayFromYaml($emailPath, $context);
    }

    protected function sendEmail(Attendee $attendee, array $context, String $templateName, String $emailType)
    {
        $plain = $this->renderToString($templateName, 'emails/'.$emailType.'.txt.twig', $context);
        $html = $this->renderToString($templateName, 'emails/'.$emailType.'.html.twig', $context);
        $meta = $this->getEmailMeta($templateName, 'emails/'.$emailType.'.yaml.twig', $context);

        $message = (new \Swift_Message($meta['subject']))
            ->setFrom($meta['email_from'])
            ->setTo($attendee->getEmail())
            ->setBody($html, 'text/html')
            ->addPart($plain, 'text/plain')
        ;

        $status = $this->get('mailer')->send($message);

        $log = new EmailLog();
        $log->setTemplate($templateName);
        $log->setContentHtml($html);
        $log->setContentPlain($plain);
        $log->setEmailAddress($attendee->getEmail());
        $log->setEmailMeta(json_encode($meta));
        $log->setEmailType($emailType);
        $log->setStatus($status);

        $em = $this->getDoctrine()->getManager();
        $em->persist($log);
        $em->flush();
    }
}
