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

    public function sendEmail($attendee, array $context, String $templateName, String $emailType)
    {
        $plain = $this->renderToString($templateName, 'emails/'.$emailType.'.txt.twig', $context);
        $html = $this->renderToString($templateName, 'emails/'.$emailType.'.html.twig', $context);
        $meta = $this->getEmailMeta($templateName, 'emails/'.$emailType.'.yaml.twig', $context);
        $email = $attendee->getEmail();
        $encodedMeta = json_encode($meta);

        $em = $this->getDoctrine()->getManager();
        $isSent = $em->getRepository(EmailLog::class)->isEmailSent($templateName, $html, $plain, $email, $encodedMeta, $emailType);
        if (!$isSent) {
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

            $em->persist($log);
            $em->flush();
        }
    }
}
