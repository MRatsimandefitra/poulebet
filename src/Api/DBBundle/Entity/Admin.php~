<?php
// src/AppBundle/Entity/User.php

namespace Api\DBBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="admin")
 */
class Admin extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(array('ROLE_ADMIN'));

        // your own logic
    }

    /**
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="prenom",type="string", length=100)
     */
    protected $prenom;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="nom",type="string", length=100)
     */
    protected $nom;

    /**
     * @ORM\Column(name="isSuperAdmin",type="boolean", nullable=true)
     */
    protected $isSuperAdmin;


    /**
     * @ORM\OneToMany(targetEntity="DroitAdmin", mappedBy="admin", cascade={"persist","remove"})
     *
     */
    protected $droitAdmin;
    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Admin
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Admin
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set isSuperAdmin
     *
     * @param boolean $isSuperAdmin
     *
     * @return Admin
     */
    public function setIsSuperAdmin($isSuperAdmin)
    {
        $this->isSuperAdmin = $isSuperAdmin;

        return $this;
    }

    /**
     * Get isSuperAdmin
     *
     * @return boolean
     */
    public function getIsSuperAdmin()
    {
        return $this->isSuperAdmin;
    }

    /**
     * Add droitAdmin
     *
     * @param \Api\DBBundle\Entity\DroitAdmin $droitAdmin
     *
     * @return Admin
     */
    public function addDroitAdmin(\Api\DBBundle\Entity\DroitAdmin $droitAdmin)
    {
        $this->droitAdmin[] = $droitAdmin;

        return $this;
    }

    /**
     * Remove droitAdmin
     *
     * @param \Api\DBBundle\Entity\DroitAdmin $droitAdmin
     */
    public function removeDroitAdmin(\Api\DBBundle\Entity\DroitAdmin $droitAdmin)
    {
        $this->droitAdmin->removeElement($droitAdmin);
    }

    /**
     * Get droitAdmin
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDroitAdmin()
    {
        return $this->droitAdmin;
    }
}
