<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Organizer.
 *
 * @ORM\Table(name="organizer")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\OrganizerRepository")
 * @UniqueEntity(
 *     fields={"deleted", "email"},
 *     errorPath="email",
 *     ignoreNull=false,
 * )
 */
class Organizer extends BaseEntity
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
     * @ORM\Column(name="email", type="string", length=254)
     * @Assert\NotBlank()
     * @Assert\Length(max=254)
     * @Assert\Email()
     */
    private $email;

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
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email.
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @ORM\OneToMany(targetEntity="EventOrganizers", mappedBy="organizer")
     */
    private $events;

    /**
     * @return mixed
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * @param mixed $events
     */
    public function setEvents($events)
    {
        $this->events = $events;
    }
}
