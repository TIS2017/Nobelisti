<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventOrganizers.
 *
 * @ORM\Table(name="event_organizers")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventOrganizersRepository")
 */
class EventOrganizers extends BaseEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="organizers")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $eventId;

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param mixed $eventId
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;
    }

    /**
     * @param mixed $organizerId
     */
    public function setOrganizerId($organizerId)
    {
        $this->organizerId = $organizerId;
    }

    /**
     * @return mixed
     */
    public function getOrganizerId()
    {
        return $this->organizerId;
    }

    /**
     * @ORM\ManyToOne(targetEntity="Organizer", inversedBy="events")
     * @ORM\JoinColumn(name="organizer_id", referencedColumnName="id")
     */
    private $organizerId;
}
