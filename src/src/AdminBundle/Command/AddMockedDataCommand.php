<?php

namespace AdminBundle\Command;

use AdminBundle\Entity\Admin;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Organizer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddMockedDataCommand extends ContainerAwareCommand
{
    private function randomFirstName()
    {
        $names = array(
            'Abdullah', 'Itzel', 'Franklin', 'Maci', 'Sierra', 'Nasir', 'Vivian', 'Jaxson',
            'Reece', 'Sofia', 'Lawson', 'Jax', 'Gaige', 'Alec', 'Nyasia', 'Yesenia', 'Branden',
            'Damon', 'Leia', 'Evelin', 'Virginia', 'Marisol', 'Charlee', 'Phoenix', 'Natasha',
            'Valentino', 'Bryanna', 'Jazmin', 'Craig', 'Jermaine', 'Jaslene', 'Raina', 'Evangeline',
            'Adalyn', 'Avery', 'Elianna', 'August', 'Lisa', 'Arthur', 'Amirah', 'Kenneth', 'Kiana',
            'Marlee', 'Messiah', 'Teagan', 'Ishaan', 'Adyson', 'Justin', 'Ross', 'Janessa',
        );

        return $names[mt_rand(0, sizeof($names) - 1)];
    }

    private function randomLastName()
    {
        $surnames = array(
            'Franco', 'Jordan', 'Griffith', 'Davies', 'Barrera', 'Goodwin', 'Bates', 'Deleon',
            'Key', 'Walter', 'Gilmore', 'Barrett', 'Roberts', 'Wells', 'Durham', 'Lane', 'Lutz',
            'Mccullough', 'Cobb', 'Banks', 'Conner', 'Cowan', 'Cortez', 'Villegas', 'Cooper',
            'Brown', 'Knight', 'Newton', 'Allison', 'Sullivan', 'Ibarra', 'Glass', 'Downs',
            'Coleman', 'Wilkins', 'Webster', 'Dickerson', 'Rivas', 'Fitzpatrick', 'Michael',
            'Mccarty', 'Sutton', 'Leon', 'Cisneros', 'Aguilar', 'Chandler', 'Jennings', 'Sparks',
            'Frye', 'Hooper',
        );

        return $surnames[mt_rand(0, sizeof($surnames) - 1)];
    }

    private function randomDate()
    {
        $random = new \DateTime();
        $random->setTimestamp(mt_rand(1202055681, 1282055681));

        return $random;
    }

    private function getRandomObject($em, $objectType)
    {
        $data = $em->getRepository($objectType)->findAll();
        shuffle($data);

        return $data[0];
    }

    private function getRandomLanguage($em)
    {
        return $this->getRandomObject($em, Language::class);
    }

    private function getRandomEvent($em)
    {
        return $this->getRandomObject($em, Event::class);
    }

    private function getRandomEventType($em)
    {
        return $this->getRandomObject($em, EventType::class);
    }

    private function makeEmail($first, $last)
    {
        return strtolower($first.'@'.$last.'.com');
    }

    private function randomEmail()
    {
        return $this->makeEmail($this->randomFirstName(), $this->randomLastName());
    }

    protected function configure()
    {
        $this->setName('nobelisti:add:mocked-data')
            ->setDescription('Adds mocked data for development purposes');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

//        $this->createAdmins($em);
//        $this->createLanguages($em);
//        $this->createOrganizers($em);
//        $this->createAttendees($em);
//        $this->createEventTypes($em);
//        $this->createEvents($em);

        // todo: $this->createRegistrations($em);
        // todo: $this->assignOrganizersToEvents($em);
        // todo: $this->assignLanguagesToEvents($em);
        // todo: $this->assignLanguagesToEventTypes($em);
        // todo: $this->createRegistrations($em);

        $em->flush();
    }

    private function createAdmins($em)
    {
        for ($i = 0; $i < 10; ++$i) {
            $admin = new Admin();
            $admin->setEmail($this->randomEmail());
            $admin->setPassword('');
            $em->persist($admin);
        }
    }

    private function createLanguages($em)
    {
        $lang1 = new Language();
        $lang1->setCode('en_US');
        $lang1->setLanguage('English');

        $lang2 = new Language();
        $lang2->setCode('sk_SK');
        $lang2->setLanguage('Slovak');

        $em->persist($lang1);
        $em->persist($lang2);
    }

    private function createOrganizers($em)
    {
        for ($i = 0; $i < 10; ++$i) {
            $organizer = new Organizer();
            $organizer->setEmail($this->randomEmail());
            $em->persist($organizer);
        }
    }

    private function createAttendees($em)
    {
        for ($i = 0; $i < 100; ++$i) {
            $attendee = new Attendee();
            $attendee->setFirstName($this->randomFirstName());
            $attendee->setLastName($this->randomLastName());
            $attendee->setLanguages($this->getRandomLanguage($em));
            $attendee->setUnsubscribed((bool) random_int(0, 1));
            $attendee->setEmail($this->makeEmail($attendee->getFirstName(), $attendee->getLastName()));
            $em->persist($attendee);
        }
    }

    private function createEventTypes($em)
    {
        for ($i = 0; $i < 5; ++$i) {
            $type = new EventType();
            $type->setTemplate('default');
            $type->setSlug(strtolower($this->randomFirstName()));
            $em->persist($type);
        }
    }

    private function createEvents($em)
    {
        for ($i = 0; $i < 10; ++$i) {
            $event = new Event();
            $event->setAddress('Kosice'.$i);
            $event->setCapacity(random_int(10, 200));
            $event->setDateTime($this->randomDate());
            $event->setEventType($this->getRandomEventType($em));
            $event->setRegistrationStart($this->randomDate());
            $event->setRegistrationEnd($this->randomDate());
            $event->setTemplateOverride('default');
            $event->setNotificationThreshold(random_int(1, 7));

            $em->persist($event);
        }
    }
}
