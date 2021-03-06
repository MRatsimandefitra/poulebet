<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Championat
 *
 * @ORM\Table(name="championat")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ChampionatRepository")
 */
class Championat
{
    
    /**
     * @ORM\ManyToMany(targetEntity="TeamsPays", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
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
     * @ORM\Column(name="nomChampionat", type="string", length=255)
     */
    private $nomChampionat;
    /**
     * @var string
     *
     * @ORM\Column(name="fullNameChampionat", type="string", length=255, nullable=true)
     */
    private $fullNameChampionat;
    /**
     * @var string
     *
     * @ORM\Column(name="typeChampionat", type="string", length=255, nullable=true)
     */
    private $typeChampionat;
    /**
     * @var string
     *
     * @ORM\Column(name="season", type="string", length=255, nullable=true)
     */
    private $season;

    /**
    * @ORM\OneToMany(targetEntity="Api\DBBundle\Entity\Matchs", mappedBy="championnat")
    */
    private $matchs;
    /**
     * @var \Date
     *
     * @ORM\Column(name="dateDebutChampionat", type="date", nullable=true)
     */
    private $dateDebutChampionat;
    /**
     * @var \Date
     *
     * @ORM\Column(name="dateFinaleChampionat", type="date", nullable=true)
     */
    private $dateFinaleChampionat;
    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=255, nullable=true)
     */
    private $pays;
    /**
     * @var Integer
     *
     * @ORM\Column(name="rang", type="integer", nullable=true)
     */
    private $rang;
    /**
     * @var Boolean
     *
     * @ORM\Column(name="isEnable", type="boolean", nullable=true)
     */
    private $isEnable;
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
     * Set nomChampionat
     *
     * @param string $nomChampionat
     *
     * @return Championat
     */
    public function setNomChampionat($nomChampionat)
    {
        $this->nomChampionat = $nomChampionat;

        return $this;
    }

    /**
     * Get nomChampionat
     *
     * @return string
     */
    public function getNomChampionat()
    {
        return $this->nomChampionat;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matchs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add match
     *
     * @param \Api\DBBundle\Entity\Matchs $match
     *
     * @return Championat
     */
    public function addMatch(\Api\DBBundle\Entity\Matchs $match)
    {
        $this->matchs[] = $match;

        return $this;
    }

    /**
     * Remove match
     *
     * @param \Api\DBBundle\Entity\Matchs $match
     */
    public function removeMatch(\Api\DBBundle\Entity\Matchs $match)
    {
        $this->matchs->removeElement($match);
    }

    /**
     * Get matchs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchs()
    {
        return $this->matchs;
    }

    /**
     * Set typeChampionat
     *
     * @param string $typeChampionat
     *
     * @return Championat
     */
    public function setTypeChampionat($typeChampionat)
    {
        $this->typeChampionat = $typeChampionat;

        return $this;
    }

    /**
     * Get typeChampionat
     *
     * @return string
     */
    public function getTypeChampionat()
    {
        return $this->typeChampionat;
    }

    /**
     * Set fullNameChampionat
     *
     * @param string $fullNameChampionat
     *
     * @return Championat
     */
    public function setFullNameChampionat($fullNameChampionat)
    {
        $this->fullNameChampionat = $fullNameChampionat;

        return $this;
    }

    /**
     * Get fullNameChampionat
     *
     * @return string
     */
    public function getFullNameChampionat()
    {
        return $this->fullNameChampionat;
    }

    /**
     * Set dateDebutChampionat
     *
     * @param \DateTime $dateDebutChampionat
     *
     * @return Championat
     */
    public function setDateDebutChampionat($dateDebutChampionat)
    {
        $this->dateDebutChampionat = $dateDebutChampionat;

        return $this;
    }

    /**
     * Get dateDebutChampionat
     *
     * @return \DateTime
     */
    public function getDateDebutChampionat()
    {
        return $this->dateDebutChampionat;
    }

    /**
     * Set dateFinaleChampionat
     *
     * @param \DateTime $dateFinaleChampionat
     *
     * @return Championat
     */
    public function setDateFinaleChampionat($dateFinaleChampionat)
    {
        $this->dateFinaleChampionat = $dateFinaleChampionat;

        return $this;
    }

    /**
     * Get dateFinaleChampionat
     *
     * @return \DateTime
     */
    public function getDateFinaleChampionat()
    {
        return $this->dateFinaleChampionat;
    }

    /**
     * Add teamsPay
     *
     * @param \Api\DBBundle\Entity\TeamsPays $teamsPay
     *
     * @return Championat
     */
    public function addTeamsPay(\Api\DBBundle\Entity\TeamsPays $teamsPay)
    {
        $this->teamsPays[] = $teamsPay;

        return $this;
    }

    /**
     * Remove teamsPay
     *
     * @param \Api\DBBundle\Entity\TeamsPays $teamsPay
     */
    public function removeTeamsPay(\Api\DBBundle\Entity\TeamsPays $teamsPay)
    {
        $this->teamsPays->removeElement($teamsPay);
    }

    /**
     * Get teamsPays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTeamsPays()
    {
        return $this->teamsPays;
    }

    /**
     * Set season
     *
     * @param string $season
     *
     * @return Championat
     */
    public function setSeason($season)
    {
        $this->season = $season;

        return $this;
    }

    /**
     * Get season
     *
     * @return string
     */
    public function getSeason()
    {
        return $this->season;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Championat
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }


    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set rang
     *
     * @param integer $rang
     *
     * @return Championat
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return integer
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set isEnable
     *
     * @param boolean $isEnable
     *
     * @return Championat
     */
    public function setIsEnable($isEnable)
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    /**
     * Get isEnable
     *
     * @return boolean
     */
    public function getIsEnable()
    {
        return $this->isEnable;
    }
}
