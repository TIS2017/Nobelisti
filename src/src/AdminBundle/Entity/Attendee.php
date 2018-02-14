<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Evence\Bundle\SoftDeleteableExtensionBundle\Mapping\Annotation as Evence;

/**
 * Attendee.
 *
 * @ORM\Table(name="attendee")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\AttendeeRepository")
 * @UniqueEntity(
 *     fields={"deleted", "email"},
 *     errorPath="email",
 *     ignoreNull=false,
 * )
 */
class Attendee extends BaseEntity
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
     * @ORM\Column(name="first_name", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var int
     *
     * @ORM\Column(name="unsubscribed", type="boolean")
     * @Assert\NotBlank()
     */
    private $unsubscribed = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @Evence\onSoftDelete(type="CASCADE")
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="attendees")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    public $language;

    /**
     * @ORM\OneToMany(targetEntity="Registration", mappedBy="attendee")
     */
    public $registrations;

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
     * Get the value of firstName.
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName.
     *
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName.
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName.
     *
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of unsubscribed.
     */
    public function getUnsubscribed()
    {
        return $this->unsubscribed;
    }

    /**
     * Set the value of unsubscribed.
     *
     * @return self
     */
    public function setUnsubscribed($unsubscribed)
    {
        $this->unsubscribed = $unsubscribed;

        return $this;
    }

    /**
     * Get the value of email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email.
     *
     * @return self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of languages.
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set the value of languageId.
     *
     * @return self
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get all registrations.
     */
    public function getRegistrations()
    {
        return $this->registrations;
    }
}
