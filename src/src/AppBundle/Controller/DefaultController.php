<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Registration;
use AppBundle\Form\RegistrationForm;
use Symfony\Component\Form\FormError;
use EmailBundle\Controller\EmailController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
            throw $this->createNotFoundException('No event type found for slug'.$slug);
        }

        if ('DEFAULT' === $_locale) { // select a default language or something
            $language = 'en_US';
            $attendeeLanguage = $this->getDoctrine()->getRepository(Language::class)->findOneBy(
                ['code' => 'en_US']
            );
        } else {
            $language = $_locale;
            $attendeeLanguage = $this->getDoctrine()->getRepository(Language::class)->findOneBy(
                ['code' => $_locale]
            );

            if (null == $attendeeLanguage) {
                return $this->redirectToRoute('frontend_index', ['slug' => $slug, '_locale' => 'en_US']);
            }

            $form = $this->getEmptyRegistraionForm($eventType);
            $form->handleRequest($request);
            $templateName = $eventType->getTemplate();
            // check language is enabled for this $eventType
            if (!self::existsLanguageFile($templateName, $language)) {
                return $this->redirectToRoute('frontend_index', ['slug' => $slug, '_locale' => 'en_US']);
            }
        }

        $em = $this->getDoctrine()->getManager();

        $registration = new Registration();
        $token = md5(time().rand()); // TODO
        $registration->setConfirmationToken($token);
        $registration->setCode(9); // TODO
        $registration->setLanguages($attendeeLanguage);

        $form = $this->getEmptyRegistraionForm($eventType);
        $form->handleRequest($request);

        $context = [
            'event_type' => $eventType,
            'form' => $form->createView(),
        ];
        $templateName = $eventType->getTemplate();

        $template = self::getTemplate($templateName, 'index.html.twig');
        $languageContext = self::getLanguageFile($templateName, $language, $context);

        $context['lang'] = $languageContext;
        $context['lang_code'] = $language;

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->getData()['email'];
            $firstName = $form->getData()['first_name'];
            $lastName = $form->getData()['last_name'];
            $event = $em->getRepository(Event::class)->find($form->getData()['event_choice']);

            $attendee = $em->getRepository(Attendee::class)->findOneBy(array('email' => $email));
            if ($attendee) {
                $attendeeRegistratedForEvent = $em->getRepository(Registration::class)->findOneBy(
                                                        ['attendee'=> $attendee, 'events' => $event]);
                if ($attendeeRegistratedForEvent) { // attendee already registered for event
                    $form->get('email')->addError(new FormError($context['lang']['already_registered']));
                    $context['form'] = $form->createView();
                    return $this->render($template, $context);
                }

            }

            $registration->setEvents($event);

            //checking capacity
            $registratedPeople = $em->getRepository(Registration::class)->findBy(['events'=> $event]);
            if ($event->getCapacity() <= count($registratedPeople)) {
                $this->addFlash('error', $context['lang']['capacity_full']);
                return $this->render($template, $context);
            }

            //checking registration end
            $registrationEnd = $event->getRegistrationEnd();
            $now = new \DateTime('now');
            if ($registrationEnd < $now) {
                $this->addFlash('error', $context['lang']['registration_closed']);
                return $this->render($template, $context);
            }


            if (!$attendee) {
                $attendee = new Attendee();
                $attendee->setEmail($email);
            }
            $attendee->setFirstName($firstName);
            $attendee->setlastName($lastName);
            $attendee->setLanguages($attendeeLanguage);
            $registration->setAttendee($attendee);

            $context['attendee'] = $attendee;
            $context['registration'] = $registration;

            $em->persist($attendee);
            $em->persist($registration);
            $em->flush();

            $this->sendEmail($attendee, $context, $templateName, 'registration');

            $this->addFlash('success', "You successfully signed up!");
            $context['form'] = $this->getEmptyRegistraionForm($eventType)->createView();
        }

        return $this->render($template, $context);
    }

    private function getEmptyRegistraionForm($eventType)
    {
        $events = $eventType->getEvents();
        $eventOptions = [];
        foreach ($events as $event) {
            $eventOptions[$event->getAddress()] = $event->getId();
        }

        $defaultData = array('first_name' => '', 'last_name' => '', 'email' => '', 'events' => $eventOptions);

        return $this->createForm(RegistrationForm::class, $defaultData);
    }
}
