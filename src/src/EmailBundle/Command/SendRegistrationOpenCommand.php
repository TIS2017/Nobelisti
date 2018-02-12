<?php

namespace EmailBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Entity\Event;
use EmailBundle\Controller\EmailController;

class SendRegistrationOpenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('email:send:registration-open')
             ->setDescription('Sends notification emails to all subscribed attendees that the registration is open.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $controller = new EmailController();
        $controller->setContainer($this->getContainer());

        $em = $this->getContainer()->get('doctrine')->getManager();
        $events = $em->getRepository(Event::class)->getTodaysOpenEvents();
        $attendees = $em->getRepository(Attendee:class)->getSubscribedAttendees();
        foreach ($events as $event) {
            $this->sendNotificationForEvent($event, $attendees, $controller);
        }
    }

    private function sendNotificationForEvent($event, $attendees, $controller)
    {
        echo 'For address '.$event->getAddress()."\n";
        echo "--------------------\n";

        $registrations = $event->getRegistrations();
        foreach ($attendees as $attendee) {
            $context = $this->getContextForNotificationEmail($event, $attendee, $controller);

            $controller->sendEmail($attendee, $context, $event->getTemplateOverride(), 'new_event');

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
        $languageContext = $controller->getLanguageFile($event->getTemplateOverride(), $languageCode, $context);

        $context['lang'] = $languageContext;
        $context['attendee'] = $attendee;

        return $context;
    }
}
