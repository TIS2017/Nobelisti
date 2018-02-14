<?php

namespace EmailBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Event;
use EmailBundle\Controller\EmailController;

class SendRegistrationOpenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('email:send:registration-open')
             ->setDescription('Sends notification emails to all subscribed attendees that the registration is open.')
             ->addArgument('event_type_id', InputArgument::OPTIONAL, 'Event Type for which the emails should be sent.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $eventTypeId = $input->getArgument('event_type_id');

        $controller = new EmailController();
        $controller->setContainer($this->getContainer());

        $em = $this->getContainer()->get('doctrine')->getManager();
        $events = $em->getRepository(Event::class)->getTodaysOpenEvents();
        if ($eventTypeId) {
            $events = array_filter($events, function ($event) use ($eventTypeId) {
                return $event->getEventType()->getId() == $eventTypeId;
            });
        }
        $attendees = $em->getRepository(Attendee::class)->getSubscribedAttendees();
        foreach ($events as $event) {
            $this->sendNotificationForEvent($event, $attendees, $controller, $output);
        }
    }

    private function sendNotificationForEvent($event, $attendees, $controller, $output)
    {
        $output->writeln('For address '.$event->getAddress());
        $output->writeln('--------------------');

        $registrations = $event->getRegistrations();
        foreach ($attendees as $attendee) {
            $context = $this->getContextForNotificationEmail($event, $attendee, $controller);

            $controller->sendEmail($attendee, $context, $event->getTemplateOverride(), 'new_event', $event->getId(), $event->getEventType()->getId());

            $output->writeln('Sent to '.$attendee->getEmail());
        }
        $output->writeln('');
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

        $registrations = $attendee->getRegistrations();
        if (count($registrations) > 0) {
            $context['registration'] = $registrations[0];
        } else {
            $context['registration'] = '';
        }

        return $context;
    }
}
