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
     * @ORM\Column(name="vote", type="integer", nullable=true)
     */
    private $vote;
    /**
     * @var Boolean
     *
     * @ORM\Column(name="isCombined", type="boolean", nullable=true)
     */
    private $isCombined;
    /**
     * @var int
     *
     * @ORM\Column(name="gainPotentiel", type="integer", nullable=true)
     */
    private $gainPotentiel;
    /**
     * @var int
     *
     * @ORM\Column(name="misetotale", type="integer", nullable=true)
     */
    private $misetotale;

    /**
     * @var bool
     *
     * @ORM\Column(name="gagnant", type="boolean", nullable=true)
     */
    private $gagnant;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMise", type="datetime", nullable=true)
     */
    private $dateMise;

    /**
     * @var \String
     *
     * @ORM\Column(name="idMise", type="string", length=255, nullable=true)
     */
    private $idMise;

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
     * Set gainPotentiel
     *
     * @param integer $gainPotentiel
     *
     * @return VoteUtilisateur
     */
    public function setGainPotentiel($gainPotentiel)
    {
        $this->gainPotentiel = $gainPotentiel;

        return $this;
    }

    /**
     * Get gainPotentiel
     *
     * @return integer
     */
    public function getGainPotentiel()
    {
        return $this->gainPotentiel;
    }

    /**
     * Set misetotale
     *
     * @param integer $misetotale
     *
     * @return VoteUtilisateur
     */
    public function setMisetotale($misetotale)
    {
        $this->misetotale = $misetotale;

        return $this;
    }

    /**
     * Get misetotale
     *
     * @return integer
     */
    public function getMisetotale()
    {
        return $this->misetotale;
    }

    /**
     * Set dateMise
     *
     * @param \DateTime $dateMise
     *
     * @return VoteUtilisateur
     */
    public function setDateMise($dateMise)
    {
        $this->dateMise = $dateMise;

        return $this;
    }

    /**
     * Get dateMise
     *
     * @return \DateTime
     */
    public function getDateMise()
    {
        return $this->dateMise;
    }

    /**
     * Set isCombined
     *
     * @param boolean $isCombined
     *
     * @return VoteUtilisateur
     */
    public function setIsCombined($isCombined)
    {
        $this->isCombined = $isCombined;

        return $this;
    }

    /**
     * Get isCombined
     *
     * @return boolean
     */
    public function getIsCombined()
    {
        return $this->isCombined;
    }

    /**
     * Set idMise
     *
     * @param string $idMise
     *
     * @return VoteUtilisateur
     */
    public function setIdMise($idMise)
    {
        $this->idMise = $idMise;

        return $this;
    }

    /**
     * Get idMise
     *
     * @return string
     */
    public function getIdMise()
    {
        return $this->idMise;
    }
}
