<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MatchsCorrespondance
 *
 * @ORM\Table(name="matchs_correspondance")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MatchsCorrespondanceRepository")
 */
class MatchsCorrespondance
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
     * @ORM\Column(name="equipeId", type="string", length=255)
     */
    private $equipeId;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeGoalApi", type="string", length=255)
     */
    private $equipeGoalApi;

    /**
     * @var string
     *
     * @ORM\Column(name="equipeNetbetSport", type="string", length=255)
     */
    private $equipeNetbetSport;


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
     * Set equipeId
     *
     * @param string $equipeId
     *
     * @return MatchsCorrespondance
     */
    public function setEquipeId($equipeId)
    {
        $this->equipeId = $equipeId;

        return $this;
    }

    /**
     * Get equipeId
     *
     * @return string
     */
    public function getEquipeId()
    {
        return $this->equipeId;
    }

    /**
     * Set equipeGoalApi
     *
     * @param string $equipeGoalApi
     *
     * @return MatchsCorrespondance
     */
    public function setEquipeGoalApi($equipeGoalApi)
    {
        $this->equipeGoalApi = $equipeGoalApi;

        return $this;
    }

    /**
     * Get equipeGoalApi
     *
     * @return string
     */
    public function getEquipeGoalApi()
    {
        return $this->equipeGoalApi;
    }

    /**
     * Set equipeNetbetSport
     *
     * @param string $equipeNetbetSport
     *
     * @return MatchsCorrespondance
     */
    public function setEquipeNetbetSport($equipeNetbetSport)
    {
        $this->equipeNetbetSport = $equipeNetbetSport;

        return $this;
    }

    /**
     * Get equipeNetbetSport
     *
     * @return string
     */
    public function getEquipeNetbetSport()
    {
        return $this->equipeNetbetSport;
    }
}
