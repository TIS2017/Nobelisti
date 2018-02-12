<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation as Evence;

/**
 * EventOrganizers.
 *
 * @ORM\Table(name="event_organizers")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventOrganizersRepository")
 * @UniqueEntity(
 *     fields={"event", "organizer", "deleted"},
 *     ignoreNull=false,
 * )
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
     * @Evence\onSoftDelete(type="CASCADE")
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="organizers")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     */
    private $event;

    /**
     * @return mixed
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param mixed $event
     */
    public function setEvent($event)
    {
        $this->event = $event;
    }

    /**
     * @param mixed $organizer
     */
    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;
    }

    /**
     * @return mixed
     */
    public function getOrganizer()
    {
        return $this->organizer;
    }

    /**
     * @Evence\onSoftDelete(type="CASCADE")
     * @ORM\ManyToOne(targetEntity="Organizer", inversedBy="events")
     * @ORM\JoinColumn(name="organizer_id", referencedColumnName="id", nullable=false)
     */
    private $organizer;
}
