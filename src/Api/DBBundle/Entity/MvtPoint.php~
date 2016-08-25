<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MvtPoint
 *
 * @ORM\Table(name="mvt_point")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MvtPointRepository")
 */
class MvtPoint
{
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist"})
     * @ORM\JoinColumn(name="utilisateur_id")
     */
    private $utilisateur;
    /**
     * @ORM\ManyToOne(targetEntity="MvtLot", cascade={"persist"})
     * @ORM\JoinColumn(name="mvtlot_id")
     */
    private $mvtLot;
    /**
     * @ORM\ManyToOne(targetEntity="VoteUtilisateur", cascade={"persist"})
     * @ORM\JoinColumn(name="voteutilisateur_id")
     */
    private $voteUtilisateur;
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
     * @ORM\Column(name="entreePoint", type="integer")
     */
    private $entreePoint;

    /**
     * @var int
     *
     * @ORM\Column(name="sortiePoint", type="integer")
     */
    private $sortiePoint;

    /**
     * @var int
     *
     * @ORM\Column(name="soldePoint", type="integer")
     */
    private $soldePoint;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMvt", type="datetime")
     */
    private $dateMvt;


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
     * Set entreePoint
     *
     * @param integer $entreePoint
     *
     * @return MvtPoint
     */
    public function setEntreePoint($entreePoint)
    {
        $this->entreePoint = $entreePoint;

        return $this;
    }

    /**
     * Get entreePoint
     *
     * @return int
     */
    public function getEntreePoint()
    {
        return $this->entreePoint;
    }

    /**
     * Set sortiePoint
     *
     * @param integer $sortiePoint
     *
     * @return MvtPoint
     */
    public function setSortiePoint($sortiePoint)
    {
        $this->sortiePoint = $sortiePoint;

        return $this;
    }

    /**
     * Get sortiePoint
     *
     * @return int
     */
    public function getSortiePoint()
    {
        return $this->sortiePoint;
    }

    /**
     * Set soldePoint
     *
     * @param integer $soldePoint
     *
     * @return MvtPoint
     */
    public function setSoldePoint($soldePoint)
    {
        $this->soldePoint = $soldePoint;

        return $this;
    }

    /**
     * Get soldePoint
     *
     * @return int
     */
    public function getSoldePoint()
    {
        return $this->soldePoint;
    }

    /**
     * Set dateMvt
     *
     * @param \DateTime $dateMvt
     *
     * @return MvtPoint
     */
    public function setDateMvt($dateMvt)
    {
        $this->dateMvt = $dateMvt;

        return $this;
    }

    /**
     * Get dateMvt
     *
     * @return \DateTime
     */
    public function getDateMvt()
    {
        return $this->dateMvt;
    }

    /**
     * Set utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return MvtPoint
     */
    public function setUtilisateur(\Api\DBBundle\Entity\Utilisateur $utilisateur = null)
    {
        $this->utilisateur = $utilisateur;

        return $this;
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
     * Set mvtLot
     *
     * @param \Api\DBBundle\Entity\MvtLot $mvtLot
     *
     * @return MvtPoint
     */
    public function setMvtLot(\Api\DBBundle\Entity\MvtLot $mvtLot = null)
    {
        $this->mvtLot = $mvtLot;

        return $this;
    }

    /**
     * Get mvtLot
     *
     * @return \Api\DBBundle\Entity\MvtLot
     */
    public function getMvtLot()
    {
        return $this->mvtLot;
    }

    /**
     * Set voteUtilisateur
     *
     * @param \Api\DBBundle\Entity\VoteUtilisateur $voteUtilisateur
     *
     * @return MvtPoint
     */
    public function setVoteUtilisateur(\Api\DBBundle\Entity\VoteUtilisateur $voteUtilisateur = null)
    {
        $this->voteUtilisateur = $voteUtilisateur;

        return $this;
    }

    /**
     * Get voteUtilisateur
     *
     * @return \Api\DBBundle\Entity\VoteUtilisateur
     */
    public function getVoteUtilisateur()
    {
        return $this->voteUtilisateur;
    }
}
