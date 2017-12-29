<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * EventType.
 *
 * @ORM\Table(name="event_type")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\EventTypeRepository")
 * @UniqueEntity(
 *     fields={"slug", "deleted"},
 *     errorPath="slug",
 *     ignoreNull=false,
 * )
 */
class EventType extends BaseEntity
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
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $slug;

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $template;

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
     * @ORM\OneToMany(targetEntity="Event", mappedBy="eventTypeId")
     */
    private $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }
}
