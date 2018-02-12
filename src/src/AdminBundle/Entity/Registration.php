<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Registration.
 *
 * @ORM\Table(name="registration")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\RegistrationRepository")
 * @UniqueEntity("confirmationToken")
 * @UniqueEntity(
 *     fields={"deleted", "confirmationToken"},
 *     errorPath="confirmationToken",
 *     ignoreNull=false,
 * )
 */
class Registration extends BaseEntity
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
     * @var int
     *
     * @ORM\Column(name="code", type="integer")
     * @Assert\NotBlank()
     */
    private $code;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirmed", type="datetime", nullable=true)
     */
    private $confirmed;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_token", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $confirmationToken;

    /**
     * @ORM\ManyToOne(targetEntity="Attendee", inversedBy="registrations")
     * @ORM\JoinColumn(name="attendee_id", referencedColumnName="id")
     */
    private $attendee;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="registrations")
     * @ORM\JoinColumn(name="event_details_id", referencedColumnName="id")
     */
    private $events;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="registrations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $languages;

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
     * Get the value of code.
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set the value of code.
     *
     * @param int $code
     *
     * @return self
     */
    public function setCode(int $code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set the value of confirmed.
     *
     * @param \DateTime $confirmed
     *
     * @return self
     */
    public function setConfirmed(\DateTime $confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get the value of confirmed.
     *
     * @return \DateTime
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Get the value of confirmationToken.
     *
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * Set the value of confirmationToken.
     *
     * @param string $confirmationToken
     *
     * @return self
     */
    public function setConfirmationToken(string $confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    /**
     * Get the value of attendee.
     */
    public function getAttendee()
    {
        return $this->attendee;
    }

    /**
     * Set the value of attendee.
     *
     * @return self
     */
    public function setAttendee($attendee)
    {
        $this->attendee = $attendee;

        return $this;
    }

    /**
     * Get the value of eventDetails.
     */
    public function getEvents()
    {
        return $this->events;
    }

    /**
     * Set the value of eventDetails.
     *
     * @return self
     */
    public function setEvents($events)
    {
        $this->events = $events;

        return $this;
    }

    /**
     * Get the value of languages.
     */
    public function getLanguages()
    {
        return $this->languages;
    }

    /**
     * Set the value of languages.
     *
     * @return self
     */
    public function setLanguages($languages)
    {
        $this->languages = $languages;

        return $this;
    }

    public function canBeConfirmedNow()
    {
        $now = new \DateTime('now');
        $diff = $now->getTimestamp() - $this->created->getTimestamp();

        return $diff <= 60 * 60 * 24;
    }
}
