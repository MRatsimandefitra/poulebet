<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * DroitAdmin
 *
 * @ORM\Table(name="droit_admin")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\DroitAdminRepository")
 *
 */
class DroitAdmin
{
    /**
     * @ORM\ManyToOne(targetEntity="Droit", cascade={"persist"})
     * @ORM\JoinColumn(name="droit_id")
     */
    private $droit;
    /**
     * @ORM\ManyToOne(targetEntity="Admin", cascade={"persist","remove"}, inversedBy="droitAdmin")
     * @ORM\JoinColumn(name="admin_id")
     */
    private $admin;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="lecture", type="boolean", nullable=true)
     */
    private $lecture;

    /**
     * @var bool
     *
     * @ORM\Column(name="modification", type="boolean", nullable=true)
     */
    private $modification;

    /**
     * @var bool
     *
     * @ORM\Column(name="suppression", type="boolean", nullable=true)
     */
    private $suppression;

    /**
     * @var bool
     *
     * @ORM\Column(name="ajout", type="boolean", nullable=true)
     */
    private $ajout;


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
     * Set lecture
     *
     * @param boolean $lecture
     *
     * @return DroitAdmin
     */
    public function setLecture($lecture)
    {
        $this->lecture = $lecture;

        return $this;
    }

    /**
     * Get lecture
     *
     * @return bool
     */
    public function getLecture()
    {
        return $this->lecture;
    }

    /**
     * Set modification
     *
     * @param boolean $modification
     *
     * @return DroitAdmin
     */
    public function setModification($modification)
    {
        $this->modification = $modification;

        return $this;
    }

    /**
     * Get modification
     *
     * @return bool
     */
    public function getModification()
    {
        return $this->modification;
    }

    /**
     * Set suppression
     *
     * @param boolean $suppression
     *
     * @return DroitAdmin
     */
    public function setSuppression($suppression)
    {
        $this->suppression = $suppression;

        return $this;
    }

    /**
     * Get suppression
     *
     * @return bool
     */
    public function getSuppression()
    {
        return $this->suppression;
    }

    /**
     * Set ajout
     *
     * @param boolean $ajout
     *
     * @return DroitAdmin
     */
    public function setAjout($ajout)
    {
        $this->ajout = $ajout;

        return $this;
    }

    /**
     * Get ajout
     *
     * @return bool
     */
    public function getAjout()
    {
        return $this->ajout;
    }


    /**
     * Set droit
     *
     * @param \Api\DBBundle\Entity\Droit $droit
     *
     * @return DroitAdmin
     */
    public function setDroit(\Api\DBBundle\Entity\Droit $droit = null)
    {
        $this->droit = $droit;

        return $this;
    }

    /**
     * Get droit
     *
     * @return \Api\DBBundle\Entity\Droit
     */
    public function getDroit()
    {
        return $this->droit;
    }

    /**
     * Set admin
     *
     * @param \Api\DBBundle\Entity\Admin $admin
     *
     * @return DroitAdmin
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
