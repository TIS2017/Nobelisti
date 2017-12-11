<?php

namespace EmailBundle\Entity;

use AdminBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * EmailLogContent.
 *
 * @ORM\Table(name="email_log_content")
 * @ORM\Entity(repositoryClass="EmailBundle\Repository\EmailLogContentRepository")
 */
class EmailLogContent extends BaseEntity
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
     * @ORM\Column(name="email_address", type="string", length=255)
     */
    private $emailAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="email_type", type="string", length=100)
     */
    private $emailType;

    /**
     * @var string
     *
     * @ORM\Column(name="email_meta", type="text", nullable=true)
     */
    private $emailMeta;

    /**
     * @var string
     *
     * @ORM\Column(name="content_plain", type="text")
     */
    private $contentPlain;

    /**
     * @var string
     *
     * @ORM\Column(name="content_html", type="text", nullable=true)
     */
    private $contentHtml;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=100)
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
     * Set emailAddress.
     *
     * @param string $emailAddress
     *
     * @return EmailLogContent
     */
    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;

        return $this;
    }

    /**
     * Get emailAddress.
     *
     * @return string
     */
    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    /**
     * Set emailType.
     *
     * @param string $emailType
     *
     * @return EmailLogContent
     */
    public function setEmailType($emailType)
    {
        $this->emailType = $emailType;

        return $this;
    }

    /**
     * Get emailType.
     *
     * @return string
     */
    public function getEmailType()
    {
        return $this->emailType;
    }

    /**
     * Set emailMeta.
     *
     * @param string $emailMeta
     *
     * @return EmailLogContent
     */
    public function setEmailMeta($emailMeta)
    {
        $this->emailMeta = $emailMeta;

        return $this;
    }

    /**
     * Get emailMeta.
     *
     * @return string
     */
    public function getEmailMeta()
    {
        return $this->emailMeta;
    }

    /**
     * Set contentPlain.
     *
     * @param string $contentPlain
     *
     * @return EmailLogContent
     */
    public function setContentPlain($contentPlain)
    {
        $this->contentPlain = $contentPlain;

        return $this;
    }

    /**
     * Get contentPlain.
     *
     * @return string
     */
    public function getContentPlain()
    {
        return $this->contentPlain;
    }

    /**
     * Set contentHtml.
     *
     * @param string $contentHtml
     *
     * @return EmailLogContent
     */
    public function setContentHtml($contentHtml)
    {
        $this->contentHtml = $contentHtml;

        return $this;
    }

    /**
     * Get contentHtml.
     *
     * @return string
     */
    public function getContentHtml()
    {
        return $this->contentHtml;
    }

    /**
     * Set template.
     *
     * @param string $template
     *
     * @return EmailLogContent
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
