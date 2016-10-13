<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeamsCoresspondance
 *
 * @ORM\Table(name="teams_coresspondance")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\TeamsCoresspondanceRepository")
 */
class TeamsCoresspondance
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
     * @ORM\Column(name="teams", type="string", length=255)
     */
    private $teams;

    /**
     * @var bool
     *
     * @ORM\Column(name="isExistInGoalApi", type="boolean")
     */
    private $isExistInGoalApi;

    /**
     * @var string
     *
     * @ORM\Column(name="teamsNameInGoalApi", type="string", length=255, nullable=true)
     */
    private $teamsNameInGoalApi;
    /**
     * @var string
     *
     * @ORM\Column(name="teamsFullNameInGoalApi", type="string", length=255, nullable=true)
     */
    private $teamsFullNameInGoalApi;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255)
     */
    private $region;


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
     * Set teams
     *
     * @param string $teams
     *
     * @return TeamsCoresspondance
     */
    public function setTeams($teams)
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * Get teams
     *
     * @return string
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Set isExistInGoalApi
     *
     * @param boolean $isExistInGoalApi
     *
     * @return TeamsCoresspondance
     */
    public function setIsExistInGoalApi($isExistInGoalApi)
    {
        $this->isExistInGoalApi = $isExistInGoalApi;

        return $this;
    }

    /**
     * Get isExistInGoalApi
     *
     * @return bool
     */
    public function getIsExistInGoalApi()
    {
        return $this->isExistInGoalApi;
    }

    /**
     * Set teamsNameInGoalApi
     *
     * @param string $teamsNameInGoalApi
     *
     * @return TeamsCoresspondance
     */
    public function setTeamsNameInGoalApi($teamsNameInGoalApi)
    {
        $this->teamsNameInGoalApi = $teamsNameInGoalApi;

        return $this;
    }

    /**
     * Get teamsNameInGoalApi
     *
     * @return string
     */
    public function getTeamsNameInGoalApi()
    {
        return $this->teamsNameInGoalApi;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return TeamsCoresspondance
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


    /**
     * Set teamsFullNameInGoalApi
     *
     * @param string $teamsFullNameInGoalApi
     *
     * @return TeamsCoresspondance
     */
    public function setTeamsFullNameInGoalApi($teamsFullNameInGoalApi)
    {
        $this->teamsFullNameInGoalApi = $teamsFullNameInGoalApi;

        return $this;
    }

    /**
     * Get teamsFullNameInGoalApi
     *
     * @return string
     */
    public function getTeamsFullNameInGoalApi()
    {
        return $this->teamsFullNameInGoalApi;
    }
}
