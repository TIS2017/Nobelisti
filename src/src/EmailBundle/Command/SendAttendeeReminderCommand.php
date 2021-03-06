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

            $controller->sendEmail($attendee, $context, $event->getTemplateOverride(), 'reminder', $event->getId(), $event->getEventType()->getId());

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

        $languageCode = $attendee->getLanguage()->getCode();
        $languageContext = $controller->getLanguageFile($event->getTemplateOverride(), $languageCode, $context);

        $context['lang'] = $languageContext;
        $context['lang_code'] = $languageCode;
        $context['attendee'] = $attendee;

        return $context;
    }
}
