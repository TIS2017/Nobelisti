<?php

namespace EmailBundle\Controller;

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

    public function sendEmailNoCheck($attendee, array $context, String $templateName, String $emailType, $eventType, $event = null)
    {
        $plain = $this->renderToString($templateName, 'emails/'.$emailType.'.txt.twig', $context);
        $html = $this->renderToString($templateName, 'emails/'.$emailType.'.html.twig', $context);
        $meta = $this->getEmailMeta($templateName, 'emails/'.$emailType.'.yaml.twig', $context);
        $email = $attendee->getEmail();
        $encodedMeta = json_encode($meta);

        $message = (new \Swift_Message($meta['subject']))
            ->setFrom($meta['email_from'])
            ->setTo($email)
            ->setBody($html, 'text/html')
            ->addPart($plain, 'text/plain')
        ;

        $status = $this->get('mailer')->send($message);

        $log = new EmailLog();
        $log->setTemplate($templateName);
        $log->setContentHtml($html);
        $log->setContentPlain($plain);
        $log->setEmailAddress($email);
        $log->setEmailMeta($encodedMeta);
        $log->setEmailType($emailType);
        $log->setStatus($status);
        $log->setEventType($eventType);
        $log->setEvent($event);

        $em = $this->getDoctrine()->getManager();
        $em->persist($log);
        $em->flush();

        return !$status;
    }

    public function sendEmail($attendee, array $context, String $templateName, String $emailType, $eventType, $event = null)
    {
        $em = $this->getDoctrine()->getManager();
        $isSent = $em->getRepository(EmailLog::class)
            ->isEmailSent($templateName, $attendee->getEmail(), $emailType, $eventType, $event);

        if ($isSent) {
            return true;
        }

        return $this->sendEmailNoCheck($attendee, $context, $templateName, $emailType, $eventType, $event);
    }
}
