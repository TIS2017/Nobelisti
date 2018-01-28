<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Event.
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventRepository")
 */
class Event extends BaseEntity
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
     * @var string
     *
     * @ORM\Column(name="address", type="text")
     */
    private $address;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_time", type="datetime")
     */
    private $dateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="registration_end", type="datetime")
     */
    private $registrationEnd;

    /**
     * @ORM\Column(name="capacity", type="integer")
     */
    private $capacity;

    /**
     * @ORM\Column(name="notification_threshold", type="integer")
     */
    private $notificationThreshold;

    /**
     * @var string
     *
     * @ORM\Column(name="template_override", type="string", length=100)
     */
    private $templateOverride;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }

    /**
     * @return \DateTime
     */
    public function getRegistrationEnd()
    {
        return $this->registrationEnd;
    }

    /**
     * @return mixed
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @return mixed
     */
    public function getNotificationThreshold()
    {
        return $this->notificationThreshold;
    }

    /**
     * @return string
     */
    public function getTemplateOverride()
    {
        return $this->templateOverride;
    }

    /**
     * @return mixed
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @param \DateTime $dateTime
     */
    public function setDateTime($dateTime)
    {
        $this->dateTime = $dateTime;
    }

    /**
     * @param \DateTime $registrationEnd
     */
    public function setRegistrationEnd($registrationEnd)
    {
        $this->registrationEnd = $registrationEnd;
    }

    /**
     * @param mixed $capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @param mixed $notificationThreshold
     */
    public function setNotificationThreshold($notificationThreshold)
    {
        $this->notificationThreshold = $notificationThreshold;
    }

    /**
     * @param string $templateOverride
     */
    public function setTemplateOverride($templateOverride)
    {
        $this->templateOverride = $templateOverride;
    }

    /**
     * @param mixed $eventType
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * @ORM\ManyToOne(targetEntity="EventType", inversedBy="events")
     * @ORM\JoinColumn(name="event_type_id", referencedColumnName="id")
     */
    private $eventType;

    /**
     * @ORM\OneToMany(targetEntity="EventOrganizers", mappedBy="event")
     */
    private $organizers;

    /**
     * @return mixed
     */
    public function getOrganizers()
    {
        return $this->organizers;
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @ORM\OneToMany(targetEntity="EventLanguages", mappedBy="event")
     */
    private $languages;

    /**
     * @ORM\OneToMany(targetEntity="Registration", mappedBy="eventDetailsId")
     */
    private $registrations;
}
