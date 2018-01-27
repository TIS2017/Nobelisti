<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EventLanguages.
 *
 * @ORM\Table(name="event_languages")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventLanguagesRepository")
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
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="languages")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     */
    private $eventId;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="events")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $languageId;
}
