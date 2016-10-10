<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationRecapitulation
 *
 * @ORM\Table(name="notification_recapitulation")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\NotificationRecapitulationRepository")
 */
class NotificationRecapitulation
{
    /**
     * @ORM\ManyToOne(targetEntity="Matchs", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $matchs;
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisateur;
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
     * @ORM\Column(name="tokenDevice", type="string", length=255, nullable=true)
     */
    private $tokenDevice;

    /**
     * @var bool
     *
     * @ORM\Column(name="isNotificationSended", type="boolean", nullable=true)
     */
    private $isNotificationSended;
    /**
     * @var bool
     *
     * @ORM\Column(name="isCombined", type="boolean", nullable=true)
     */
    private $isCombined;
    /**
     * @var \Integer
     *
     * @ORM\Column(name="nbMatchs", type="integer", nullable=true)
     */
    private $nbMatchs;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;


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
     * Set tokenDevice
     *
     * @param string $tokenDevice
     *
     * @return NotificationRecapitulation
     */
    public function setTokenDevice($tokenDevice)
    {
        $this->tokenDevice = $tokenDevice;

        return $this;
    }

    /**
     * Get tokenDevice
     *
     * @return string
     */
    public function getTokenDevice()
    {
        return $this->tokenDevice;
    }

    /**
     * Set isNotificationSended
     *
     * @param boolean $isNotificationSended
     *
     * @return NotificationRecapitulation
     */
    public function setIsNotificationSended($isNotificationSended)
    {
        $this->isNotificationSended = $isNotificationSended;

        return $this;
    }

    /**
     * Get isNotificationSended
     *
     * @return bool
     */
    public function getIsNotificationSended()
    {
        return $this->isNotificationSended;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return NotificationRecapitulation
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

    /**
     * Set matchs
     *
     * @param \Api\DBBundle\Entity\Matchs $matchs
     *
     * @return NotificationRecapitulation
     */
    public function setMatchs(\Api\DBBundle\Entity\Matchs $matchs = null)
    {
        $this->matchs = $matchs;

        return $this;
    }

    /**
     * Get matchs
     *
     * @return \Api\DBBundle\Entity\Matchs
     */
    public function getMatchs()
    {
        return $this->matchs;
    }

    /**
     * Set utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return NotificationRecapitulation
     */
    public function setUtilisateur(\Api\DBBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Get utilisateur
     *
     * @return \Api\DBBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set isCombined
     *
     * @param boolean $isCombined
     *
     * @return NotificationRecapitulation
     */
    public function setIsCombined($isCombined)
    {
        $this->isCombined = $isCombined;

        return $this;
    }

    /**
     * Get isCombined
     *
     * @return boolean
     */
    public function getIsCombined()
    {
        return $this->isCombined;
    }

    /**
     * Set nbMatchs
     *
     * @param integer $nbMatchs
     *
     * @return NotificationRecapitulation
     */
    public function setNbMatchs($nbMatchs)
    {
        $this->nbMatchs = $nbMatchs;

        return $this;
    }

    /**
     * Get nbMatchs
     *
     * @return integer
     */
    public function getNbMatchs()
    {
        return $this->nbMatchs;
    }
}
