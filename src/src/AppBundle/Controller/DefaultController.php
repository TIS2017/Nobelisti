<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\EventType;
use AppBundle\Entity\Attendee;
use AppBundle\Form\RegistrationForm;
use EmailBundle\Entity\EmailLog;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use TemplateBundle\Controller\CustomTemplateController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class DefaultController extends CustomTemplateController
{
    /**
     * @Route("/{slug}/{_locale}", name="frontend_index", defaults={"_locale": "DEFAULT"})
     * @Method({"GET", "POST"})
     */
    public function indexAction($slug, $_locale, Request $request)
    {
        $eventType = $this->getDoctrine()->getRepository(EventType::class)->findOneBy(
            ['slug' => $slug]
        );

        if (!$eventType) {
            throw $this->createNotFoundException(
                'No event type found for slug '.$slug
            );
        }

        if ('DEFAULT' === $_locale) {
            $language = 'sk_SK'; // todo, select a default language or something
        } else {
            $language = $_locale;
            // todo: check language is enabled for this $eventType
        }

        $attendee = new Attendee();

        // default values for development
        $attendee->setFirstName('Zoltan');
        $attendee->setLastName('Onody');
        $attendee->setEmail('zoltan.onody@gmail.com');

        $form = $this->createForm(RegistrationForm::class, $attendee);
        $form->handleRequest($request);

        $context = [
            'event_type' => $eventType,
            'form' => $form->createView(),
        ];

        $templateName = $eventType->getTemplate();

        $template = self::getTemplate($templateName, 'index.html.twig');
        $languageContext = self::getLanguageFile($templateName, $language, $context);

        $context['lang'] = $languageContext;

        if ($form->isSubmitted() && $form->isValid()) {
            $context['attendee'] = $attendee;

            $this->sendEmail($attendee, $context, $templateName, 'registration');

            return;
        }

        return $this->render($template, $context);
    }

    private function sendEmail(Attendee $attendee, array $context, String $templateName, String $emailType)
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
