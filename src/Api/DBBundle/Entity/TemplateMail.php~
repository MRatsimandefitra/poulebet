<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TemplateMail
 *
 * @ORM\Table(name="template_mail")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\TemplateMailRepository")
 */
class TemplateMail
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
     * @ORM\Column(name="libelleMail", type="string", length=255)
     */
    private $libelleMail;

    /**
     * @var string
     *
     * @ORM\Column(name="objectMail", type="string", length=255)
     */
    private $objectMail;

    /**
     * @var string
     *
     * @ORM\Column(name="messages", type="text")
     */
    private $messages;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime('now'));
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set libelleMail
     *
     * @param string $libelleMail
     *
     * @return TemplateMail
     */
    public function setLibelleMail($libelleMail)
    {
        $this->libelleMail = $libelleMail;

        return $this;
    }

    /**
     * Get libelleMail
     *
     * @return string
     */
    public function getLibelleMail()
    {
        return $this->libelleMail;
    }

    /**
     * Set objectMail
     *
     * @param string $objectMail
     *
     * @return TemplateMail
     */
    public function setObjectMail($objectMail)
    {
        $this->objectMail = $objectMail;

        return $this;
    }

    /**
     * Get objectMail
     *
     * @return string
     */
    public function getObjectMail()
    {
        return $this->objectMail;
    }

    /**
     * Set messages
     *
     * @param string $messages
     *
     * @return TemplateMail
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }

    /**
     * Get messages
     *
     * @return string
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TemplateMail
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return TemplateMail
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
