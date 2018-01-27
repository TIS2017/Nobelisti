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
     * @var int
     *
     * @ORM\Column(name="confirmed", type="datetime")
     * @Assert\NotBlank()
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
    private $attendeeId;

    /**
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="registrations")
     * @ORM\JoinColumn(name="event_details_id", referencedColumnName="id")
     */
    private $eventDetailsId;

    /**
     * @ORM\ManyToOne(targetEntity="Language", inversedBy="registrations")
     * @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     */
    private $languageId;

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
     * @param int $confirmed
     *
     * @return self
     */
    public function setConfirmed(int $confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get the value of confirmed.
     *
     * @return int
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
     * Get the value of atendeeId.
     */
    public function getAtendeeId()
    {
        return $this->atendeeId;
    }

    /**
     * Set the value of atendeeId.
     *
     * @return self
     */
    public function setAtendeeId($atendeeId)
    {
        $this->atendeeId = $atendeeId;

        return $this;
    }

    /**
     * Get the value of eventDetailsId.
     */
    public function getEventDetailsId()
    {
        return $this->eventDetailsId;
    }

    /**
     * Set the value of eventDetailsId.
     *
     * @return self
     */
    public function setEventDetailsId($eventDetailsId)
    {
        $this->eventDetailsId = $eventDetailsId;

        return $this;
    }

    /**
     * Get the value of languageId.
     */
    public function getLanguageId()
    {
        return $this->languageId;
    }

    /**
     * Set the value of languageId.
     *
     * @return self
     */
    public function setLanguageId($languageId)
    {
        $this->languageId = $languageId;

        return $this;
    }
}
