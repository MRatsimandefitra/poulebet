<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MvtCredit
 *
 * @ORM\Table(name="mvt_credit")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MvtCreditRepository")
 */
class MvtCredit
{
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="utilisateur_id", nullable=true)
     */
    private $utilisateur;
    /**
     * @ORM\ManyToOne(targetEntity="VoteUtilisateur", cascade={"persist","remove"})
     */
    private $voteUtilisateur;
    /**
     * @ORM\ManyToOne(targetEntity="Credit", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="credit_id", nullable=true)
     */
    /*private $credit;*/

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
     * @ORM\Column(name="entreeCredit", type="integer", nullable=true)
     */
    private $entreeCredit;

    /**
     * @var int
     *
     * @ORM\Column(name="sortieCredit", type="integer", nullable=true)
     */
    private $sortieCredit;

    /**
     * @var int
     *
     * @ORM\Column(name="soldeCredit", type="integer", nullable=true)
     */
    private $soldeCredit;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMvt", type="datetime", nullable=true)
     */
    private $dateMvt;
    /**
     * @var \String
     *
     * @ORM\Column(name="typeCredit", type="string", length=255, nullable=true)
     */
    private $typeCredit;
    /**
     * @var \String
     *
     * @ORM\Column(name="credit", type="string", length=255, nullable=true)
     */
    private $credit;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set entreeCredit
     *
     * @param integer $entreeCredit
     *
     * @return MvtCredit
     */
    public function setEntreeCredit($entreeCredit)
    {
        $this->entreeCredit = $entreeCredit;

        return $this;
    }

    /**
     * Get entreeCredit
     *
     * @return integer
     */
    public function getEntreeCredit()
    {
        return $this->entreeCredit;
    }

    /**
     * Set sortieCredit
     *
     * @param integer $sortieCredit
     *
     * @return MvtCredit
     */
    public function setSortieCredit($sortieCredit)
    {
        $this->sortieCredit = $sortieCredit;

        return $this;
    }

    /**
     * Get sortieCredit
     *
     * @return integer
     */
    public function getSortieCredit()
    {
        return $this->sortieCredit;
    }

    /**
     * Set soldeCredit
     *
     * @param integer $soldeCredit
     *
     * @return MvtCredit
     */
    public function setSoldeCredit($soldeCredit)
    {
        $this->soldeCredit = $soldeCredit;

        return $this;
    }

    /**
     * Get soldeCredit
     *
     * @return integer
     */
    public function getSoldeCredit()
    {
        return $this->soldeCredit;
    }

    /**
     * Set dateMvt
     *
     * @param \DateTime $dateMvt
     *
     * @return MvtCredit
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
     * Set typeCredit
     *
     * @param string $typeCredit
     *
     * @return MvtCredit
     */
    public function setTypeCredit($typeCredit)
    {
        $this->typeCredit = $typeCredit;

        return $this;
    }

    /**
     * Get typeCredit
     *
     * @return string
     */
    public function getTypeCredit()
    {
        return $this->typeCredit;
    }

    /**
     * Set credit
     *
     * @param string $credit
     *
     * @return MvtCredit
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return MvtCredit
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
     * Set voteUtilisateur
     *
     * @param \Api\DBBundle\Entity\VoteUtilisateur $voteUtilisateur
     *
     * @return MvtCredit
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
