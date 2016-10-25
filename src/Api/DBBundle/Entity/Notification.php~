<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\NotificationRepository")
 */
class Notification
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
     * @ORM\Column(name="message", type="text", nullable=true)
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToMany(targetEntity="Utilisateur")
     * @ORM\JoinTable(name="notification_utilisateur")
     **/
    private $utilisateurs;

    /**
     * @ORM\ManyToOne(targetEntity="Admin")
     * @ORM\JoinColumn(name="admin_id")
     * 
     */
    private $admin; 
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
     * Set message
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
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
     * Constructor
     */
    public function __construct()
    {
        $this->utilisateurs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return Notification
     */
    public function addUtilisateur(\Api\DBBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     */
    public function removeUtilisateur(\Api\DBBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateurs()
    {
        return $this->utilisateurs->toArray();
    }
    /**
     * Add utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return Notification
     */
    public function addUtilisateurCollection(\Api\DBBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs[] = $utilisateur;

        return $this;
    }

    /**
     * Remove utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     */
    public function removeUtilisateurCollection(\Api\DBBundle\Entity\Utilisateur $utilisateur)
    {
        $this->utilisateurs->removeElement($utilisateur);
    }

    /**
     * Get utilisateurs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUtilisateursCollection()
    {
        return $this->utilisateurs;
    }

    /**
     * Set admin
     *
     * @param \Api\DBBundle\Entity\Admin $admin
     *
     * @return Notification
     */
    public function setAdmin(\Api\DBBundle\Entity\Admin $admin = null)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get admin
     *
     * @return \Api\DBBundle\Entity\Admin
     */
    public function getAdmin()
    {
        return $this->admin;
    }
}
