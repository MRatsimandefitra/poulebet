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
    * @ORM\OneToMany(targetEntity="Api\DBBundle\Entity\Matchs", mappedBy="championnat")
    */
    private $matchs;
    
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
}
