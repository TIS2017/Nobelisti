<?php

namespace EmailBundle\Entity;

use AdminBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * EmailLogContent.
 *
 * @ORM\Table(name="email_log_content")
 * @ORM\Entity(repositoryClass="EmailBundle\Repository\EmailLogRepository")
 */
class EmailLog extends BaseEntity
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
     * @var string
     *
     * @ORM\Column(name="status", type="text")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="event_type", type="integer", nullable=true)
     */
    private $eventType;

    /**
     * @var int
     *
     * @ORM\Column(name="event", type="integer", nullable=true)
     */
    private $event;

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
     * @return EmailLog
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
     * @return EmailLog
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
     * @return EmailLog
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
     * @return EmailLog
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
     * @return EmailLog
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
     * @return EmailLog
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

    /**
     * Set status.
     *
     * @param string $status
     *
     * @return EmailLog
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set event
     *
     * @param int $event
     *
     * @return EmailLog
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event.
     *
     * @return int
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set event type
     *
     * @param int $eventType
     *
     * @return EmailLog
     */
    public function setEventType($eventType)
    {
        $this->eventType = $eventType;

        return $this;
    }

    /**
     * Get event type.
     *
     * @return int
     */
    public function getEventType()
    {
        return $this->eventType;
    }
}
