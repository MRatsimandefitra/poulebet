<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Teams
 *
 * @ORM\Table(name="teams")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\TeamsRepository")
 */
class Teams
{
    /**
     * @ORM\ManyToOne(targetEntity="TeamsPays", cascade={"persist"})
     */
    private $teamsPays;

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
     * @ORM\Column(name="nomClub", type="string", length=255)
     */
    private $nomClub;

    /**
     * @var string
     *
     * @ORM\Column(name="idNameClub", type="string", length=255)
     */
    private $idNameClub;

    /**
     * @var string
     *
     * @ORM\Column(name="fullNameClub", type="string", length=255)
     */
    private $fullNameClub;
    /**
     * @var string
     *
     * @ORM\Column(name="codePays", type="string", length=255, nullable=true)
     */
    private $codePays;
    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

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
     * Set nomClub
     *
     * @param string $nomClub
     *
     * @return Teams
     */
    public function setNomClub($nomClub)
    {
        $this->nomClub = $nomClub;

        return $this;
    }

    /**
     * Get nomClub
     *
     * @return string
     */
    public function getNomClub()
    {
        return $this->nomClub;
    }



    /**
     * Set fullNameClub
     *
     * @param string $fullNameClub
     *
     * @return Teams
     */
    public function setFullNameClub($fullNameClub)
    {
        $this->fullNameClub = $fullNameClub;

        return $this;
    }

    /**
     * Get fullNameClub
     *
     * @return string
     */
    public function getFullNameClub()
    {
        return $this->fullNameClub;
    }

    /**
     * Set teamsPays
     *
     * @param \Api\DBBundle\Entity\TeamsPays $teamsPays
     *
     * @return Teams
     */
    public function setTeamsPays(\Api\DBBundle\Entity\TeamsPays $teamsPays = null)
    {
        $this->teamsPays = $teamsPays;

        return $this;
    }

    /**
     * Get teamsPays
     *
     * @return \Api\DBBundle\Entity\TeamsPays
     */
    public function getTeamsPays()
    {
        return $this->teamsPays;
    }

    /**
     * Set idNameClub
     *
     * @param string $idNameClub
     *
     * @return Teams
     */
    public function setIdNameClub($idNameClub)
    {
        $this->idNameClub = $idNameClub;

        return $this;
    }

    /**
     * Get idNameClub
     *
     * @return string
     */
    public function getIdNameClub()
    {
        return $this->idNameClub;
    }

    /**
     * Set codePays
     *
     * @param string $codePays
     *
     * @return Teams
     */
    public function setCodePays($codePays)
    {
        $this->codePays = $codePays;

        return $this;
    }

    /**
     * Get codePays
     *
     * @return string
     */
    public function getCodePays()
    {
        return $this->codePays;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return Teams
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
