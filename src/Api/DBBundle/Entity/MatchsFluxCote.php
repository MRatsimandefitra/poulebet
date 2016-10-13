<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatchsFluxCote
 *
 * @ORM\Table(name="matchs_flux_cote")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MatchsFluxCoteRepository")
 */
class MatchsFluxCote
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateMatch", type="datetime")
     */
    private $dateMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeDomicile", type="string", length=255)
     */
    private $equipeDomicile;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeVisiteur", type="string", length=255)
     */
    private $equipeVisiteur;

    /**
     * @var string
     *
     * @ORM\Column(name="championat", type="string", length=255)
     */
    private $championat;
    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255)
     */
    private $region;

    /**
     * @var float
     *
     * @ORM\Column(name="cote1", type="float")
     */
    private $cote1;

    /**
     * @var float
     *
     * @ORM\Column(name="cote2", type="float")
     */
    private $cote2;

    /**
     * @var float
     *
     * @ORM\Column(name="coteN", type="float")
     */
    private $coteN;


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
     * Set dateMatch
     *
     * @param \DateTime $dateMatch
     *
     * @return MatchsFluxCote
     */
    public function setDateMatch($dateMatch)
    {
        $this->dateMatch = $dateMatch;

        return $this;
    }

    /**
     * Get dateMatch
     *
     * @return \DateTime
     */
    public function getDateMatch()
    {
        return $this->dateMatch;
    }

    /**
     * Set equipeDomicile
     *
     * @param string $equipeDomicile
     *
     * @return MatchsFluxCote
     */
    public function setEquipeDomicile($equipeDomicile)
    {
        $this->equipeDomicile = $equipeDomicile;

        return $this;
    }

    /**
     * Get equipeDomicile
     *
     * @return string
     */
    public function getEquipeDomicile()
    {
        return $this->equipeDomicile;
    }

    /**
     * Set equipeVisiteur
     *
     * @param string $equipeVisiteur
     *
     * @return MatchsFluxCote
     */
    public function setEquipeVisiteur($equipeVisiteur)
    {
        $this->equipeVisiteur = $equipeVisiteur;

        return $this;
    }

    /**
     * Get equipeVisiteur
     *
     * @return string
     */
    public function getEquipeVisiteur()
    {
        return $this->equipeVisiteur;
    }

    /**
     * Set championat
     *
     * @param string $championat
     *
     * @return MatchsFluxCote
     */
    public function setChampionat($championat)
    {
        $this->championat = $championat;

        return $this;
    }

    /**
     * Get championat
     *
     * @return string
     */
    public function getChampionat()
    {
        return $this->championat;
    }

    /**
     * Set cote1
     *
     * @param float $cote1
     *
     * @return MatchsFluxCote
     */
    public function setCote1($cote1)
    {
        $this->cote1 = $cote1;

        return $this;
    }

    /**
     * Get cote1
     *
     * @return float
     */
    public function getCote1()
    {
        return $this->cote1;
    }

    /**
     * Set cote2
     *
     * @param float $cote2
     *
     * @return MatchsFluxCote
     */
    public function setCote2($cote2)
    {
        $this->cote2 = $cote2;

        return $this;
    }

    /**
     * Get cote2
     *
     * @return float
     */
    public function getCote2()
    {
        return $this->cote2;
    }

    /**
     * Set coteN
     *
     * @param float $coteN
     *
     * @return MatchsFluxCote
     */
    public function setCoteN($coteN)
    {
        $this->coteN = $coteN;

        return $this;
    }

    /**
     * Get coteN
     *
     * @return float
     */
    public function getCoteN()
    {
        return $this->coteN;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return MatchsFluxCote
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }
}
