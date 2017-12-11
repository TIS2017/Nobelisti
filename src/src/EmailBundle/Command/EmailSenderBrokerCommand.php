<?php

namespace EmailBundle\Command;

use EmailBundle\RabbitMQ;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EmailSenderBrokerCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('email:sender-broker')
            ->setDescription('Broker listens to rabbitmq and sends e-mails when they come to queue');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'E-mail sender broker started',
            '============================',
        ]);

        RabbitMQ::processMessages();
    }
}
