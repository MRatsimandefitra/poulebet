<?php





namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Match
 *
 * @ORM\Table(name="matchs")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MatchsRepository")
 */
class Matchs
{

    /**
     * @ORM\ManyToOne(targetEntity="Teams", cascade={"persist"})
     */
    private $teamsDomicile;
    /**
     * @ORM\ManyToOne(targetEntity="Teams", cascade={"persist"})
     */
    private $teamsVisiteur;

    /**
     * @ORM\ManyToOne(targetEntity="Championat", cascade={"persist"})
     * @ORM\JoinColumn(name="championat_id")
     */
    private $championat;

    /**
     * @ORM\ManyToMany(targetEntity="Concours")
     * @ORM\JoinTable(name="matchs_concours")
     **/
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
     * @var String
     *
     * @ORM\Column(name="id", type="string", length=50, unique=true, nullable=false)
     * @ORM\Id
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMatch", type="datetime", nullable=true)
     */
    private $dateMatch;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestampDateMatch", type="string", nullable=true)
     */
    private $timestampDateMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeDomicile", type="string", length=50, nullable=true)
     */
    private $equipeDomicile;

    /**
     * @var string
     *
     * @ORM\Column(name="cheminLogoDomicile", type="string", length=255, nullable=true)
     */
    private $cheminLogoDomicile;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=15, nullable=true)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeVisiteur", type="string", length=50, nullable=true)
     */
    private $equipeVisiteur;

    /**
     * @var string
     *
     * @ORM\Column(name="cheminLogoVisiteur", type="string", length=255, nullable=true)
     */
    private $cheminLogoVisiteur;

    /**
     * @var float
     *
     * @ORM\Column(name="cot1Pronostic", type="float", nullable=true)
     */
    private $cot1Pronostic;

    /**
     * @var float
     *
     * @ORM\Column(name="coteNPronistic", type="float", nullable=true)
     */
    private $coteNPronistic;

    /**
     * @var float
     *
     * @ORM\Column(name="cote2Pronostic", type="float", nullable=true)
     */
    private $cote2Pronostic;

    /**
     * @var string
     *
     * @ORM\Column(name="status_match", type="string", length=50, nullable=true)
     */
    private $statusMatch;

    /**
     * @var bool
     *
     * @ORM\Column(name="masterProno", type="boolean", nullable=true, nullable=true)
     */
    private $masterProno;
    /**
     * @var bool
     *
     * @ORM\Column(name="master_prono_1", type="boolean", nullable=true, nullable=true)
     */
    private $masterProno1;
    /**
     * @var bool
     *
     * @ORM\Column(name="master_prono_n", type="boolean", nullable=true, nullable=true)
     */
    private $masterPronoN;
    /**
     * @var bool
     *
     * @ORM\Column(name="master_prono_2", type="boolean", nullable=true, nullable=true)
     */
    private $masterProno2;

    /**
     * @var bool
     *
     * @ORM\Column(name="resultatDomicile", type="integer", nullable=true, nullable=true)
     */
    private $resultatDomicile;
    /**
     * @var bool
     *
     * @ORM\Column(name="resultatVisiteur", type="integer", nullable=true, nullable=true)
     */
    private $resultatVisiteur;
    /**
     * @var bool
     *
     * @ORM\Column(name="tempsEcoules", type="integer", nullable=true, nullable=true)
     */
    private $tempsEcoules;
    /**
     * @var float
     *
     * @ORM\Column(name="vote1Concours", type="float", nullable=true)
     */
    private $vote1Concours;
    /**
     * @var float
     *
     * @ORM\Column(name="coteNConcours", type="float", nullable=true)
     */
    private $coteNConcours;
    /**
     * @var float
     *
     * @ORM\Column(name="cote2Concours", type="float", nullable=true)
     */
    private $cote2Concours;
    /**
     * @var string
     *
     * @ORM\Column(name="season", type="string", length=50, nullable=true)
     */
    private $season;
    /**
     * @var string
     *
     * @ORM\Column(name="period", type="string", length=50, nullable=true)
     */
    private $period;
    /**
     * @var string
     *
     * @ORM\Column(name="minute", type="string", length=50, nullable=true)
     */
    private $minute;
    /**
     * @var Boolean
     *
     * @ORM\Column(name="stateGoalApi", type="boolean", nullable=true)
     */
    private $stateGoalApi;

    /**
    * @ORM\OneToMany(targetEntity="Api\DBBundle\Entity\MatchsEvent", mappedBy="matchs")
    */
    private $matchsEvents;


    /**
     * Set id
     *
     * @param string $id
     *
     * @return Matchs
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set equipeDomicile
     *
     * @param string $equipeDomicile
     *
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * Set masterProno
     *
     * @param boolean $masterProno
     *
     * @return Matchs
     */
    public function setMasterProno($masterProno)
    {
        $this->masterProno = $masterProno;

        return $this;
    }

    /**
     * Get masterProno
     *
     * @return boolean
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * @return Matchs
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
     * Set lotoFoot7
     *
     * @param \Api\DBBundle\Entity\LotoFoot7 $lotoFoot7
     *
     * @return Matchs
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
     * @return Matchs
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


    /**
     * Set statusMatch
     *
     * @param string $statusMatch
     *
     * @return Matchs
     */
    public function setStatusMatch($statusMatch)
    {
        $this->statusMatch = $statusMatch;

        return $this;
    }

    /**
     * Get statusMatch
     *
     * @return string
     */
    public function getStatusMatch()
    {
        return $this->statusMatch;
    }

    /**
     * Set dateMatch
     *
     * @param \DateTime $dateMatch
     *
     * @return Matchs
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
     * Set masterProno1
     *
     * @param boolean $masterProno1
     *
     * @return Matchs
     */
    public function setMasterProno1($masterProno1)
    {
        $this->masterProno1 = $masterProno1;

        return $this;
    }

    /**
     * Get masterProno1
     *
     * @return boolean
     */
    public function getMasterProno1()
    {
        return $this->masterProno1;
    }

    /**
     * Set masterPronoN
     *
     * @param boolean $masterPronoN
     *
     * @return Matchs
     */
    public function setMasterPronoN($masterPronoN)
    {
        $this->masterPronoN = $masterPronoN;

        return $this;
    }

    /**
     * Get masterPronoN
     *
     * @return boolean
     */
    public function getMasterPronoN()
    {
        return $this->masterPronoN;
    }

    /**
     * Set masterProno2
     *
     * @param boolean $masterProno2
     *
     * @return Matchs
     */
    public function setMasterProno2($masterProno2)
    {
        $this->masterProno2 = $masterProno2;

        return $this;
    }

    /**
     * Get masterProno2
     *
     * @return boolean
     */
    public function getMasterProno2()
    {
        return $this->masterProno2;
    }

    /**
     * Set teamsDomicile
     *
     * @param \Api\DBBundle\Entity\Teams $teamsDomicile
     *
     * @return Matchs
     */
    public function setTeamsDomicile(\Api\DBBundle\Entity\Teams $teamsDomicile = null)
    {
        $this->teamsDomicile = $teamsDomicile;

        return $this;
    }

    /**
     * Get teamsDomicile
     *
     * @return \Api\DBBundle\Entity\Teams
     */
    public function getTeamsDomicile()
    {
        return $this->teamsDomicile;
    }

    /**
     * Set teamsVisiteur
     *
     * @param \Api\DBBundle\Entity\Teams $teamsVisiteur
     *
     * @return Matchs
     */
    public function setTeamsVisiteur(\Api\DBBundle\Entity\Teams $teamsVisiteur = null)
    {
        $this->teamsVisiteur = $teamsVisiteur;

        return $this;
    }

    /**
     * Get teamsVisiteur
     *
     * @return \Api\DBBundle\Entity\Teams
     */
    public function getTeamsVisiteur()
    {
        return $this->teamsVisiteur;
    }

    /**
     * Set season
     *
     * @param string $season
     *
     * @return Matchs
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
     * Constructor
     */
    public function __construct()
    {
        $this->concours = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add concour
     *
     * @param \Api\DBBundle\Entity\Concours $concour
     *
     * @return Matchs
     */
    public function addConcour(\Api\DBBundle\Entity\Concours $concour)
    {
        $this->concours[] = $concour;

        return $this;
    }

    /**
     * Remove concour
     *
     * @param \Api\DBBundle\Entity\Concours $concour
     */
    public function removeConcour(\Api\DBBundle\Entity\Concours $concour)
    {
        $this->concours->removeElement($concour);
    }

    /**
     * Get concours
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConcours()
    {
        return $this->concours;
    }

    /**
     * Set period
     *
     * @param string $period
     *
     * @return Matchs
     */
    public function setPeriod($period)
    {
        $this->period = $period;

        return $this;
    }

    /**
     * Get period
     *
     * @return string
     */
    public function getPeriod()
    {
        return $this->period;
    }

    /**
     * Set minute
     *
     * @param string $minute
     *
     * @return Matchs
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;

        return $this;
    }

    /**
     * Get minute
     *
     * @return string
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * Set stateGoalApi
     *
     * @param boolean $stateGoalApi
     *
     * @return Matchs
     */
    public function setStateGoalApi($stateGoalApi)
    {
        $this->stateGoalApi = $stateGoalApi;

        return $this;
    }

    /**
     * Get stateGoalApi
     *
     * @return boolean
     */
    public function getStateGoalApi()
    {
        return $this->stateGoalApi;
    }

    /**
     * Add matchsEvent
     *
     * @param \Api\DBBundle\Entity\MatchsEvent $matchsEvent
     *
     * @return Matchs
     */
    public function addMatchsEvent(\Api\DBBundle\Entity\MatchsEvent $matchsEvent)
    {
        $this->matchsEvents[] = $matchsEvent;

        return $this;
    }

    /**
     * Remove matchsEvent
     *
     * @param \Api\DBBundle\Entity\MatchsEvent $matchsEvent
     */
    public function removeMatchsEvent(\Api\DBBundle\Entity\MatchsEvent $matchsEvent)
    {
        $this->matchsEvents->removeElement($matchsEvent);
    }

    /**
     * Get matchsEvents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchsEvents()
    {
        return $this->matchsEvents;
    }


    /**
     * Set timestampDateMatch
     *
     * @param string $timestampDateMatch
     *
     * @return Matchs
     */
    public function setTimestampDateMatch($timestampDateMatch)
    {
        $this->timestampDateMatch = $timestampDateMatch;

        return $this;
    }

    /**
     * Get timestampDateMatch
     *
     * @return string
     */
    public function getTimestampDateMatch()
    {
        return $this->timestampDateMatch;
    }
}
