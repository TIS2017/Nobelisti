<?php

namespace AdminBundle\Command;

use AdminBundle\Entity\Admin;
use AdminBundle\Entity\Attendee;
use AdminBundle\Entity\Event;
use AdminBundle\Entity\EventLanguages;
use AdminBundle\Entity\EventOrganizers;
use AdminBundle\Entity\EventType;
use AdminBundle\Entity\EventTypeLanguages;
use AdminBundle\Entity\Language;
use AdminBundle\Entity\Organizer;
use AdminBundle\Entity\Registration;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddMockedDataCommand extends ContainerAwareCommand
{
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $this->createAdmins($em);
        $this->createLanguages($em);
        $this->createOrganizers($em);
        $this->createAttendees($em);
        $this->createEventTypes($em);
        $this->createEvents($em);

        $this->assignOrganizersToEvents($em);
        $this->assignLanguagesToEvents($em);
        $this->assignLanguagesToEventTypes($em);

        $this->createRegistrations($em);
    }

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

    /*
     * $after = DateTime
     * $offset_from =  min offset + $after
     * $offset_to = max offset + $after
     */
    private function randomDate($after = null, $offset_from = 0, $offset_to = 126144000)
    {
        if (null == $after) {
            $timestamp_from = 1451606400;
        } else {
            $timestamp_from = $after->getTimestamp();
        }

        $random = new \DateTime();
        $random->setTimestamp(mt_rand($timestamp_from + $offset_from, $timestamp_from + $offset_to));

        return $random;
    }

    private function getRandomObject($em, $objectType)
    {
        return $this->getRandomObjects($em, $objectType, 1)[0];
    }

    private function getRandomObjects($em, $objectType, $count)
    {
        $data = $em->getRepository($objectType)->findAll();

        $count = min($count, count($data));
        shuffle($data);

        return array_slice($data, 0, $count);
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

    private function createAdmins($em)
    {
        for ($i = 0; $i < 10; ++$i) {
            $admin = new Admin();
            $admin->setEmail($this->randomEmail());
            $admin->setPassword('');
            $em->persist($admin);
        }
        $em->flush();
    }

    private function createLanguages($em)
    {
        $data = [
            array('code' => 'en_US', 'lang' => 'English'),
            array('code' => 'sk_SK', 'lang' => 'Slovak'),
            array('code' => 'es_ES', 'lang' => 'Spanish'),
            array('code' => 'hu_HU', 'lang' => 'Hungarian'),
            array('code' => 'cz_CZ', 'lang' => 'Czech'),
        ];

        foreach ($data as $lang) {
            $language = new Language();
            $language->setCode($lang['code']);
            $language->setLanguage($lang['lang']);
            $em->persist($language);
        }
        $em->flush();
    }

    private function createOrganizers($em)
    {
        for ($i = 0; $i < 10; ++$i) {
            $organizer = new Organizer();
            $organizer->setEmail($this->randomEmail());
            $em->persist($organizer);
        }
        $em->flush();
    }

    private function createAttendees($em)
    {
        for ($i = 0; $i < 100; ++$i) {
            $attendee = new Attendee();
            $attendee->setFirstName($this->randomFirstName());
            $attendee->setLastName($this->randomLastName());
            $attendee->setLanguages($this->getRandomObject($em, Language::class));
            $attendee->setUnsubscribed((bool) random_int(0, 1));
            $attendee->setEmail($this->makeEmail($attendee->getFirstName(), $attendee->getLastName()));
            $em->persist($attendee);
        }
        $em->flush();
    }

    private function createEventTypes($em)
    {
        for ($i = 0; $i < 5; ++$i) {
            $type = new EventType();
            $type->setTemplate('default');
            $type->setSlug(strtolower($this->randomFirstName().random_int(0, 100)));
            $em->persist($type);
        }
        $em->flush();
    }

    private function createEvents($em)
    {
        $cities = ['Kosice', 'Bratislava', 'Nitra', 'Banska Bistrica'];
        $capacity = [100, 200, 20, 50];

        $eventTypes = $em->getRepository(EventType::class)->findAll();
        foreach ($eventTypes as $eventType) {
            for ($i = 0; $i < random_int(0, 4); ++$i) {
                $event = new Event();
                $event->setAddress($cities[$i]);
                $event->setCapacity($capacity[$i]);
                $event->setEventType($eventType);
                $event->setTemplateOverride('default');
                $event->setNotificationThreshold(random_int(1, 7));

                $registrationStart = $this->randomDate();
                $registrationEnds = $this->randomDate($registrationStart, 60 * 60 * 24 * 7, 60 * 60 * 24 * 14);
                $eventDate = $this->randomDate($registrationEnds, 60 * 60 * 24 * 2, 60 * 60 * 24 * 7);

                $event->setDateTime($eventDate);
                $event->setRegistrationStart($registrationStart);
                $event->setRegistrationEnd($registrationEnds);

                $em->persist($event);
            }
        }
        $em->flush();
    }

    private function createRegistrations($em)
    {
        $eventTypes = $em->getRepository(EventType::class)->findAll();
        foreach ($eventTypes as $eventType) {
            foreach ($eventType->getEvents() as $event) {
                $attendees = $this->getRandomObjects($em, Attendee::class, random_int(0, $event->getCapacity()));

                foreach ($attendees as $attendee) {
                    $registration = new Registration();
                    $registration->setAttendee($attendee);
                    $registration->setEvents($event);
                    $registration->setCode(random_int(1000, 5000));
                    $registration->generateConfirmationToken();

                    if (0 != random_int(0, 5)) {
                        $registration->setConfirmed(new \DateTime());
                    }

                    $eventLanguages = $em->getRepository(EventLanguages::class)->findBy(['event' => $event->getId()]);
                    shuffle($eventLanguages);
                    $registration->setLanguages($eventLanguages[0]->getLanguage());

                    $em->persist($registration);
                }
            }
        }
        $em->flush();
    }

    private function assignOrganizersToEvents($em)
    {
        $events = $em->getRepository(Event::class)->findAll();
        foreach ($events as $event) {
            $organizers = $this->getRandomObjects($em, Organizer::class, random_int(1, 5));

            foreach ($organizers as $organizer) {
                $eo = new EventOrganizers();
                $eo->setEvent($event);
                $eo->setOrganizer($organizer);
                $em->persist($eo);
            }
        }
        $em->flush();
    }

    private function assignLanguagesToEvents($em)
    {
        $events = $em->getRepository(Event::class)->findAll();
        foreach ($events as $event) {
            $languages = $this->getRandomObjects($em, Language::class, random_int(1, 5));

            foreach ($languages as $language) {
                $el = new EventLanguages();
                $el->setEvent($event);
                $el->setLanguage($language);
                $em->persist($el);
            }
        }
        $em->flush();
    }

    private function assignLanguagesToEventTypes($em)
    {
        $eventTypes = $em->getRepository(EventType::class)->findAll();
        foreach ($eventTypes as $eventType) {
            $languages = $this->getRandomObjects($em, Language::class, random_int(1, 5));

            foreach ($languages as $language) {
                $etl = new EventTypeLanguages();
                $etl->setEventType($eventType);
                $etl->setLanguage($language);
                $em->persist($etl);
            }
        }
        $em->flush();
    }
}
