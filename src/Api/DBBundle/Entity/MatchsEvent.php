<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatchsEvent
 *
 * @ORM\Table(name="matchs_event")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MatchsEventRepository")
 */
class MatchsEvent
{
    /**
     * @ORM\ManyToOne(targetEntity="Teams", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $teams;

    /**
     * @ORM\ManyToOne(targetEntity="Matchs", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true,name="matchs_id")
     */
    private $matchs;
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
     * @ORM\Column(name="player", type="string", length=255)
     */
    private $player;

    /**
     * @var int
     *
     * @ORM\Column(name="minute", type="integer")
     */
    private $minute;

    /**
     * @var string
     *
     * @ORM\Column(name="score", type="string", length=255, nullable=true)
     */
    private $score;

    /**
     * @var string
     *
     * @ORM\Column(name="teamsScore", type="string", length=255, nullable=true)
     */
    private $teamsScore;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;


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
     * Set player
     *
     * @param string $player
     *
     * @return MatchsEvent
     */
    public function setPlayer($player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return string
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set minute
     *
     * @param integer $minute
     *
     * @return MatchsEvent
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;

        return $this;
    }

    /**
     * Get minute
     *
     * @return int
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * Set score
     *
     * @param string $score
     *
     * @return MatchsEvent
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
     * Set teamsScore
     *
     * @param string $teamsScore
     *
     * @return MatchsEvent
     */
    public function setTeamsScore($teamsScore)
    {
        $this->teamsScore = $teamsScore;

        return $this;
    }

    /**
     * Get teamsScore
     *
     * @return string
     */
    public function getTeamsScore()
    {
        return $this->teamsScore;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return MatchsEvent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set matchs
     *
     * @param \Api\DBBundle\Entity\Matchs $matchs
     *
     * @return MatchsEvent
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
     * Set teams
     *
     * @param \Api\DBBundle\Entity\Teams $teams
     *
     * @return MatchsEvent
     */
    public function setTeams(\Api\DBBundle\Entity\Teams $teams = null)
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * Get teams
     *
     * @return \Api\DBBundle\Entity\Teams
     */
    public function getTeams()
    {
        return $this->teams;
    }
}
