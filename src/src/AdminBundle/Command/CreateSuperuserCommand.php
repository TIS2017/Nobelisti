<?php

namespace AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Entity\Admin;

class CreateSuperuserCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('admin:create-superuser')
             ->setDescription('Creates a new Admin user')
             ->setHelp('Create a new Administrator. Use it to log in to the admin website.')
             ->addArgument('email', InputArgument::REQUIRED, 'E-mail of the Administrator.')
             ->addArgument('password', InputArgument::REQUIRED, 'Password of the Administrator.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newAdmin = new Admin($input->getArgument('email'));
        $encoder = $this->getContainer()->get('security.encoder_factory')->getEncoder($newAdmin);
        $newAdmin->setPassword($encoder->encodePassword($input->getArgument('password'), $newAdmin->getSalt()));
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $manager->persist($newAdmin);
        $manager->flush();
    }
}