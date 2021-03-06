<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * matchIndividuel
 *
 * @ORM\Table(name="match_individuel")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\matchIndividuelRepository")
 */
class matchIndividuel
{
    /**
     * @ORM\ManyToOne(targetEntity="Championat", cascade={"persist"})
     * @ORM\JoinColumn(name="championat_id")
     */
    private $championat;

    /**
     * @ORM\ManyToOne(targetEntity="Concours", cascade={"persist"})
     * @ORM\JoinColumn(name="concours_id")
     */
    private $concours;

    /**
     * @ORM\ManyToOne(targetEntity="LotoFoot7", cascade={"persist"})
     * @ORM\JoinColumn(name="lotofoot7_id")
     */
    private $lotoFoot7;

    /**
     * @ORM\ManyToOne(targetEntity="LotoFoot15", cascade={"persist"})
     * @ORM\JoinColumn(name="lotofoot15_id")
     */
    private $lotoFoot15;

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
     * @ORM\Column(name="dateMatch", type="date")
     */
    private $dateMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeDomicile", type="string", length=50)
     */
    private $equipeDomicile;

    /**
     * @var string
     *
     * @ORM\Column(name="cheminLogoDomicile", type="string", length=255)
     */
    private $cheminLogoDomicile;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=5)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeVisiteur", type="string", length=50)
     */
    private $equipeVisiteur;

    /**
     * @var string
     *
     * @ORM\Column(name="cheminLogoVisiteur", type="string", length=255)
     */
    private $cheminLogoVisiteur;

    /**
     * @var float
     *
     * @ORM\Column(name="cot1Pronostic", type="float")
     */
    private $cot1Pronostic;

    /**
     * @var float
     *
     * @ORM\Column(name="coteNPronistic", type="float")
     */
    private $coteNPronistic;

    /**
     * @var float
     *
     * @ORM\Column(name="cote2Pronostic", type="float")
     */
    private $cote2Pronostic;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="masterProno", type="boolean", nullable=true)
     */
    private $masterProno;

    /**
     * @var bool
     *
     * @ORM\Column(name="resultatDomicile", type="integer", nullable=true)
     */
    private $resultatDomicile;
    /**
     * @var bool
     *
     * @ORM\Column(name="resultatVisiteur", type="integer", nullable=true)
     */
    private $resultatVisiteur;
    /**
     * @var bool
     *
     * @ORM\Column(name="tempsEcoules", type="integer", nullable=true)
     */
    private $tempsEcoules;
    /**
     * @var float
     *
     * @ORM\Column(name="vote1Concours", type="float")
     */
    private $vote1Concours;
    /**
     * @var float
     *
     * @ORM\Column(name="coteNConcours", type="float")
     */
    private $coteNConcours;
    /**
     * @var float
     *
     * @ORM\Column(name="cote2Concours", type="float")
     */
    private $cote2Concours;


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
     * @return matchIndividuel
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
     * @return matchIndividuel
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
     * Set cheminLogoDomicile
     *
     * @param string $cheminLogoDomicile
     *
     * @return matchIndividuel
     */
    public function setCheminLogoDomicile($cheminLogoDomicile)
    {
        $this->cheminLogoDomicile = $cheminLogoDomicile;

        return $this;
    }

    /**
     * Get cheminLogoDomicile
     *
     * @return string
     */
    public function getCheminLogoDomicile()
    {
        return $this->cheminLogoDomicile;
    }

    /**
     * Set score
     *
     * @param string $score
     *
     * @return matchIndividuel
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set equipeVisiteur
     *
     * @param string $equipeVisiteur
     *
     * @return matchIndividuel
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
     * Set cheminLogoVisiteur
     *
     * @param string $cheminLogoVisiteur
     *
     * @return matchIndividuel
     */
    public function setCheminLogoVisiteur($cheminLogoVisiteur)
    {
        $this->cheminLogoVisiteur = $cheminLogoVisiteur;

        return $this;
    }

    /**
     * Get cheminLogoVisiteur
     *
     * @return string
     */
    public function getCheminLogoVisiteur()
    {
        return $this->cheminLogoVisiteur;
    }

    /**
     * Set cot1Pronostic
     *
     * @param float $cot1Pronostic
     *
     * @return matchIndividuel
     */
    public function setCot1Pronostic($cot1Pronostic)
    {
        $this->cot1Pronostic = $cot1Pronostic;

        return $this;
    }

    /**
     * Get cot1Pronostic
     *
     * @return float
     */
    public function getCot1Pronostic()
    {
        return $this->cot1Pronostic;
    }

    /**
     * Set coteNPronistic
     *
     * @param float $coteNPronistic
     *
     * @return matchIndividuel
     */
    public function setCoteNPronistic($coteNPronistic)
    {
        $this->coteNPronistic = $coteNPronistic;

        return $this;
    }

    /**
     * Get coteNPronistic
     *
     * @return float
     */
    public function getCoteNPronistic()
    {
        return $this->coteNPronistic;
    }

    /**
     * Set cote2Pronostic
     *
     * @param float $cote2Pronostic
     *
     * @return matchIndividuel
     */
    public function setCote2Pronostic($cote2Pronostic)
    {
        $this->cote2Pronostic = $cote2Pronostic;

        return $this;
    }

    /**
     * Get cote2Pronostic
     *
     * @return float
     */
    public function getCote2Pronostic()
    {
        return $this->cote2Pronostic;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return matchIndividuel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set masterProno
     *
     * @param boolean $masterProno
     *
     * @return matchIndividuel
     */
    public function setMasterProno($masterProno)
    {
        $this->masterProno = $masterProno;

        return $this;
    }

    /**
     * Get masterProno
     *
     * @return bool
     */
    public function getMasterProno()
    {
        return $this->masterProno;
    }

    /**
     * Set resultatDomicile
     *
     * @param integer $resultatDomicile
     *
     * @return matchIndividuel
     */
    public function setResultatDomicile($resultatDomicile)
    {
        $this->resultatDomicile = $resultatDomicile;

        return $this;
    }

    /**
     * Get resultatDomicile
     *
     * @return integer
     */
    public function getResultatDomicile()
    {
        return $this->resultatDomicile;
    }

    /**
     * Set resultatVisiteur
     *
     * @param integer $resultatVisiteur
     *
     * @return matchIndividuel
     */
    public function setResultatVisiteur($resultatVisiteur)
    {
        $this->resultatVisiteur = $resultatVisiteur;

        return $this;
    }

    /**
     * Get resultatVisiteur
     *
     * @return integer
     */
    public function getResultatVisiteur()
    {
        return $this->resultatVisiteur;
    }

    /**
     * Set tempsEcoules
     *
     * @param integer $tempsEcoules
     *
     * @return matchIndividuel
     */
    public function setTempsEcoules($tempsEcoules)
    {
        $this->tempsEcoules = $tempsEcoules;

        return $this;
    }

    /**
     * Get tempsEcoules
     *
     * @return integer
     */
    public function getTempsEcoules()
    {
        return $this->tempsEcoules;
    }

    /**
     * Set vote1Concours
     *
     * @param float $vote1Concours
     *
     * @return matchIndividuel
     */
    public function setVote1Concours($vote1Concours)
    {
        $this->vote1Concours = $vote1Concours;

        return $this;
    }

    /**
     * Get vote1Concours
     *
     * @return float
     */
    public function getVote1Concours()
    {
        return $this->vote1Concours;
    }

    /**
     * Set coteNConcours
     *
     * @param float $coteNConcours
     *
     * @return matchIndividuel
     */
    public function setCoteNConcours($coteNConcours)
    {
        $this->coteNConcours = $coteNConcours;

        return $this;
    }

    /**
     * Get coteNConcours
     *
     * @return float
     */
    public function getCoteNConcours()
    {
        return $this->coteNConcours;
    }

    /**
     * Set cote2Concours
     *
     * @param float $cote2Concours
     *
     * @return matchIndividuel
     */
    public function setCote2Concours($cote2Concours)
    {
        $this->cote2Concours = $cote2Concours;

        return $this;
    }

    /**
     * Get cote2Concours
     *
     * @return float
     */
    public function getCote2Concours()
    {
        return $this->cote2Concours;
    }

    /**
     * Set championat
     *
     * @param \Api\DBBundle\Entity\Championat $championat
     *
     * @return matchIndividuel
     */
    public function setChampionat(\Api\DBBundle\Entity\Championat $championat = null)
    {
        $this->championat = $championat;

        return $this;
    }

    /**
     * Get championat
     *
     * @return \Api\DBBundle\Entity\Championat
     */
    public function getChampionat()
    {
        return $this->championat;
    }

    /**
     * Set concours
     *
     * @param \Api\DBBundle\Entity\Concours $concours
     *
     * @return matchIndividuel
     */
    public function setConcours(\Api\DBBundle\Entity\Concours $concours = null)
    {
        $this->concours = $concours;

        return $this;
    }

    /**
     * Get concours
     *
     * @return \Api\DBBundle\Entity\Concours
     */
    public function getConcours()
    {
        return $this->concours;
    }

    /**
     * Set lotoFoot7
     *
     * @param \Api\DBBundle\Entity\LotoFoot7 $lotoFoot7
     *
     * @return matchIndividuel
     */
    public function setLotoFoot7(\Api\DBBundle\Entity\LotoFoot7 $lotoFoot7 = null)
    {
        $this->lotoFoot7 = $lotoFoot7;

        return $this;
    }

    /**
     * Get lotoFoot7
     *
     * @return \Api\DBBundle\Entity\LotoFoot7
     */
    public function getLotoFoot7()
    {
        return $this->lotoFoot7;
    }

    /**
     * Set lotoFoot15
     *
     * @param \Api\DBBundle\Entity\LotoFoot15 $lotoFoot15
     *
     * @return matchIndividuel
     */
    public function setLotoFoot15(\Api\DBBundle\Entity\LotoFoot15 $lotoFoot15 = null)
    {
        $this->lotoFoot15 = $lotoFoot15;

        return $this;
    }

    /**
     * Get lotoFoot15
     *
     * @return \Api\DBBundle\Entity\LotoFoot15
     */
    public function getLotoFoot15()
    {
        return $this->lotoFoot15;
    }
}
