<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\DeviceRepository")
 */
class Device
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
     * @ORM\Column(name="token", type="text", nullable=true)
     */
    private $token;


    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"remove"}, inversedBy="devices")
     * @ORM\JoinColumn(name="utilisateur_id", onDelete="CASCADE")
     */
    private $utilisateur;
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
     * Set token
     *
     * @param string $token
     *
     * @return Device
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return Device
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
}
