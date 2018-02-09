<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Registration;
use AppBundle\Form\RegistrationForm;
use Symfony\Component\Form\FormError;
use EmailBundle\Controller\EmailController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use \Datetime;

class DefaultController extends EmailController
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

        $em = $this->getDoctrine()->getManager();

        $attendee = new Attendee();
        $registration = new Registration();
        $token = md5(time() . rand()); //TODO
        $registration->setAttendee($attendee);
        $registration->setConfirmationToken($token);
        $registration->setCode(9); // TODO
        $date = new \DateTime('now');
        $registration->setConfirmed($date); // TODO migracia

        $events = $em->getRepository(EventType::class)->findOneBy(array('slug' => $slug))->getEvents();
        $eventOptions = [];
        foreach($events as $event) {
            $eventOptions[] = $event->getAddress();
        }

        $defaultData = array('first_name' => '', 'last_name' => '', 'email' => '', 'events' => $eventOptions);

        $form = $this->createForm(RegistrationForm::class, $defaultData);
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
            $email = $form->getData()['email'];
            $firstName = $form->getData()['first_name'];
            $lastName = $form->getData()['last_name'];

            // TODO - porovnavat na attendee,event
            $attendeeExists = $em->getRepository(Attendee::class)->findOneBy(array('email' => $email));
            if ($attendeeExists) {
                // attendee already registered for event
                // TODO not working
                $form->get('email')->addError(new FormError('Attendee already exists.'));
            } else {
                $attendee->setFirstName($firstName);
                $attendee->setlastName($lastName);
                $attendee->setEmail($email);

                $context['attendee'] = $attendee;
                $context['registration'] = $registration;

                $em->persist($attendee);
                $em->persist($registration);
                $em->flush();

                $this->sendEmail($attendee, $context, $templateName, 'registration');

                // todo: show success page or something
            }
        }

        return $this->render($template, $context);
    }
}
