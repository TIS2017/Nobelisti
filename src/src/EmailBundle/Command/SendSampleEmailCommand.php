<?php

namespace EmailBundle\Command;

use EmailBundle\EmailMessageCoder;
use EmailBundle\RabbitMQ;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class SendSampleEmailCommand extends Command
{
    protected function configure()
    {
        $this
            ->addArgument("id", InputArgument::REQUIRED, 'ID of message to be send.')
            ->setName('email:send-sample-email')
            ->setDescription('Send sample emails to message queue')
            ->setHelp('This command allows you to manually create a message in message queue');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Sample email generator',
            '======================',
        ]);

        $emailId = $input->getArgument('id');
        RabbitMQ::sendMessage(EmailMessageCoder::encode($emailId));

        $output->writeln('Email ' .  $emailId . ' sent.');
    }
}
