<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation as Evence;

/**
 * EventLanguages.
 *
 * @ORM\Table(name="event_languages")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventLanguagesRepository")
 * @UniqueEntity(
 *     fields={"event", "language", "deleted"},
 *     ignoreNull=false,
 * )
 */
class EventLanguages extends BaseEntity
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
     * @return mixed
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param mixed $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * @Evence\onSoftDelete(type="CASCADE")
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="languages")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id", nullable=false)
     */
    private $event;

    /**
     * @Evence\onSoftDelete(type="CASCADE")
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="events")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id", nullable=false)
     */
    private $language;
}
