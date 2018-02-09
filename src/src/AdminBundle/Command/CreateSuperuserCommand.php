<?php

namespace AdminBundle\Command;

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
        $newAdmin = new Admin();
        $newAdmin->setEmail($input->getArgument('email'));
        $password = $input->getArgument('password');
        $encoded = $this->getContainer()->get('security.password_encoder')->encodePassword($newAdmin, $password);
        $newAdmin->setPassword($encoded);
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $manager->persist($newAdmin);
        $manager->flush();
    }
}
