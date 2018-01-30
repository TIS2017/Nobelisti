<?php

namespace AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Language.
 *
 * @ORM\Table(name="language")
 * @ORM\Entity(repositoryClass="AdminBundle\Repository\LanguageRepository")
 * @UniqueEntity(
 *     fields={"deleted", "language"},
 *     errorPath="language",
 *     ignoreNull=false,
 * )
 * @UniqueEntity(
 *     fields={"deleted", "code"},
 *     errorPath="code",
 *     ignoreNull=false,
 * )
 */
class Language extends BaseEntity
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
     * @ORM\Column(name="language", type="string", length=50)
     * @Assert\NotBlank()
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=6)
     * @Assert\NotBlank()
     */
    private $code;

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
     * Set language.
     *
     * @param string $language
     *
     * @return Language
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language.
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set code.
     *
     * @param string $code
     *
     * @return Language
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @ORM\OneToMany(targetEntity="EventLanguages", mappedBy="languages")
     */
    private $events;

    /**
     * @ORM\OneToMany(targetEntity="Registration", mappedBy="languages")
     */
    private $registrations;

    /**
     * @ORM\OneToMany(targetEntity="Attendee", mappedBy="languages")
     */
    private $attendees;
}
