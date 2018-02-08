<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventTypeLanguages.
 *
 * @ORM\Table(name="event_type_languages")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventTypeLanguagesRepository")
 */
class EventTypeLanguages
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
     * @ORM\ManyToOne(targetEntity="EventType", inversedBy="languages")
     * @ORM\JoinColumn(name="event_type_id", referencedColumnName="id")
     */
    private $eventType;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="eventTypes")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $language;

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
     * Set eventType.
     *
     * @param mixed $eventType
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;
    }

    /**
     * Get eventType.
     *
     * @return mixed
     */
    public function getEventType()
    {
        return $this->eventType;
    }

    /**
     * Set language.
     *
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Get language.
     *
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }
}
