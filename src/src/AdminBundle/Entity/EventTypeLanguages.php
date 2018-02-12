<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation as Evence;

/**
 * EventTypeLanguages.
 *
 * @ORM\Table(name="event_type_languages")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventTypeLanguagesRepository")
 * @UniqueEntity(
 *     fields={"eventType", "language", "deleted"},
 *     ignoreNull=false,
 * )
 */
class EventTypeLanguages extends BaseEntity
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
     * @Evence\onSoftDelete(type="CASCADE")
     * @ORM\ManyToOne(targetEntity="EventType", inversedBy="languages")
     * @ORM\JoinColumn(name="event_type_id", referencedColumnName="id", nullable=false)
     */
    private $eventType;

    /**
     * @Evence\onSoftDelete(type="CASCADE")
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="eventTypes")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
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
