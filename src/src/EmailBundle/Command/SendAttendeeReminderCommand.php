<?php

namespace EmailBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Entity\Event;
use EmailBundle\Controller\EmailController;

class SendAttendeeReminderCommand extends ContainerAwareCommand
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

    private function sendNotificationForEvent($event, $controller)
    {
        echo 'For address '.$event->getAddress()."\n";
        echo "--------------------\n";

        $registrations = $event->getRegistrations();
        foreach ($registrations as $registration) {
            $attendee = $registration->getAttendee();
            $context = $this->getContextForNotificationEmail($event, $attendee, $controller);

            $controller->sendEmail($attendee, $context, $event->getTemplateOverride(), 'reminder');

            echo 'Sent to '.$attendee->getEmail()."\n";
        }

        echo "\n";
    }

    private function getContextForNotificationEmail($event, $attendee, $controller)
    {
        $context = [
            'event' => $event,
            'event_type' => $event->getEventType(),
        ];

        $languageCode = $attendee->getLanguages()->getCode();
        // Warning: file_get_contents(../templates/template1/languages/en_US.yaml.twig): failed to open stream: No such file or directory
        $languageContext = $controller->getLanguageFile($event->getTemplateOverride(), $languageCode, $context);

        $context['lang'] = $languageContext;
        $context['attendee'] = $attendee;

        return $context;
    }
}
