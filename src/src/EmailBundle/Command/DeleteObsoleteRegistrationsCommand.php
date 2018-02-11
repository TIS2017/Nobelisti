<?php

namespace EmailBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AdminBundle\Entity\Registration;

class DeleteObsoleteRegistrationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('email:delete:obsolete-registrations')
             ->setDescription('Deletes expired and unconfirmed registrations.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $registrations = $em->getRepository(Registration::class)->findAll();
        foreach ($registrations as $registration) {
            if (!$registration->canBeConfirmedNow()) {
                $em->remove($registration);
            }
        }
        $em->flush();
    }
}
