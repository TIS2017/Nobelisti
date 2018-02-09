<?php

namespace AdminBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
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
     * @Assert\Regex("/^[a-zA-Z0-9\-]+$/", message="Slug can contain only alphanumerical characters and a hyphen")
     * @Assert\Regex("/^admin$/", match=false, message="Slug can not be admin")
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
     * @ORM\OneToMany(targetEntity="Event", mappedBy="eventType")
     */
    private $events;

    /**
     * Get events.
     */
    public function getEvents()
    {
        return $this->events;
    }

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * @ORM\OneToMany(targetEntity="EventTypeLanguages", mappedBy="eventType")
     */
    private $languages;
}
