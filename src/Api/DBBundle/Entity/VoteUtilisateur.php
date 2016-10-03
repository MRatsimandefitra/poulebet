<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VoteUtilisateur
 *
 * @ORM\Table(name="vote_utilisateur")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\VoteUtilisateurRepository")
 */
class VoteUtilisateur
{
    /**
     * @ORM\ManyToOne(targetEntity="Matchs", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $matchs;


    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist"})
     * @ORM\JoinColumn(name="utilisateur_id")
     */
    private $utilisateur;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="vote", type="integer")
     */
    private $vote;
    /**
     * @var Boolean
     *
     * @ORM\Column(name="isVote", type="boolean", nullable=true)
     */
    private $isVote;

    /**
     * @var bool
     *
     * @ORM\Column(name="gagnant", type="boolean", nullable=true)
     */
    private $gagnant;


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
     * Set vote
     *
     * @param integer $vote
     *
     * @return VoteUtilisateur
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set gagnant
     *
     * @param boolean $gagnant
     *
     * @return VoteUtilisateur
     */
    public function setGagnant($gagnant)
    {
        $this->gagnant = $gagnant;

        return $this;
    }

    /**
     * Get gagnant
     *
     * @return bool
     */
    public function getGagnant()
    {
        return $this->gagnant;
    }



    /**
     * Get utilisateur
     *
     * @return \Api\DBBundle\Entity\Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }

    /**
     * Set matchs
     *
     * @param \Api\DBBundle\Entity\Matchs $matchs
     *
     * @return VoteUtilisateur
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
     * Set utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return VoteUtilisateur
     */
    public function setUtilisateur(\Api\DBBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * Set isVote
     *
     * @param boolean $isVote
     *
     * @return VoteUtilisateur
     */
    public function setIsVote($isVote)
    {
        $this->isVote = $isVote;

        return $this;
    }

    /**
     * Get isVote
     *
     * @return boolean
     */
    public function getIsVote()
    {
        return $this->isVote;
    }
}
