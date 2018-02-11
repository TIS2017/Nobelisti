<?php

namespace EmailBundle\Command;

use AdminBundle\Entity\EventType;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Entity\Admin;

use EmailBundle\Controller\EmailController;

class SendDailyNotificationCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('email:send:organizer-notification')
            ->setDescription('Sends notification email for organizers');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $controller = new EmailController();
        $controller->setContainer($this->getContainer());

        $em = $this->getContainer()->get('doctrine')->getManager();
        $eventTypes = $em->getRepository(EventType::class)->findAll();
        foreach ($eventTypes as $eventType) {
            $this->sendNotificationForEventType($eventType, $controller);
        }

    }

    private function sendNotificationForEventType($eventType, $controller){
        echo "Sending email for ".$eventType->getSlug()."\n";
        foreach ($eventType->getEvents() as $event) {
           $this->sendNotificationForEvent($event, $controller);
        }
        echo "===================================\n";
    }

    private function sendNotificationForEvent($event, $controller){
        echo "For address ".$event->getAddress() ."\n";
        echo "--------------------\n";

        $context = $this->getContextForNotificationEmail($event);
        foreach ($event->getOrganizers() as $eventOrganizer) {
            $organizer = $eventOrganizer->getOrganizer();

            $controller->sendEmail($organizer, $context, $event->getTemplateOverride(), "daily_notification");

            echo "Sent to ".$organizer->getEmail()."\n";
        }

        echo "\n";
    }

    private function getContextForNotificationEmail($event) {
        return array(); # todo
    }
}
