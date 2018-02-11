<?php

namespace AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Entity\Event;
use EmailBundle\Controller\EmailController;

class SendNotificationEmailsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('email:send:attendee-notification')
             ->setDescription('Sends notification emails to all registered attendees.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $controller = new EmailController();
        $controller->setContainer($this->getContainer());

        $em = $this->getContainer()->get('doctrine')->getManager();
        $events = $em->getRepository(Event::class)->getNearingEvents();
        foreach ($events as $event) {
            $this->sendNotificationForEvent($event, $controller);
        }
    }

    private function sendNotificationForEvent($event, $controller){
        echo "For address ".$event->getAddress() ."\n";
        echo "--------------------\n";

        $registrations = $event->getRegistrations();
        foreach ($registrations as $registration) {
            $attendee = $registration->getAttendee();
            $context = $this->getContextForNotificationEmail($event, $attendee);

            $controller->sendEmail($attendee, $context, $event->getTemplateOverride(), "reminder");

            echo "Sent to ".$attendee->getEmail()."\n";
        }

        echo "\n";
    }

    private function getContextForNotificationEmail($event, $attendee) {
        $context = [
            'event' => $event,
            'event_type' => $event->getEventType(),
            'attendee' => $attendee,
        ];

        $languageCode = $attendee->getLanguages()->getCode();
        $languageContext = EmailController::getLanguageFile($event-getTemplateOverride(), $languageCode, $context);

        $context['lang'] = $languageContext;

        return $context;
    }
}
