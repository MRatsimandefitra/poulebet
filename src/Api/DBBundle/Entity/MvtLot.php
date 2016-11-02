<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MvtLot
 *
 * @ORM\Table(name="mvt_lot")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MvtLotRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class MvtLot
{
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist"})
     * @ORM\JoinColumn(name="utilisateur_id")
     */
    private $utilisateur;
    /**
     * @ORM\ManyToOne(targetEntity="Lot", cascade={"persist"}, inversedBy="mvtLots")
     * @ORM\JoinColumn(name="lot_id", onDelete="CASCADE")
     */
    private $lot; 
    
    /**
     * @ORM\OneToOne(targetEntity="AddressLivraison", mappedBy="mvtLot")
     */
    private $addressLivraison;
    
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
     * @ORM\Column(name="entreeLot", type="integer")
     */
    private $entreeLot;

    /**
     * @var int
     *
     * @ORM\Column(name="sortieLot", type="integer")
     */
    private $sortieLot;

    /**
     * @var int
     *
     * @ORM\Column(name="soldeLot", type="integer")
     */
    private $soldeLot;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateMvtLot", type="datetime")
     */
    private $dateMvtLot;


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
     * Set entreeLot
     *
     * @param integer $entreeLot
     *
     * @return MvtLot
     */
    public function setEntreeLot($entreeLot)
    {
        $this->entreeLot = $entreeLot;

        return $this;
    }

    /**
     * Get entreeLot
     *
     * @return int
     */
    public function getEntreeLot()
    {
        return $this->entreeLot;
    }

    /**
     * Set sortieLot
     *
     * @param integer $sortieLot
     *
     * @return MvtLot
     */
    public function setSortieLot($sortieLot)
    {
        $this->sortieLot = $sortieLot;

        return $this;
    }

    /**
     * Get sortieLot
     *
     * @return int
     */
    public function getSortieLot()
    {
        return $this->sortieLot;
    }

    /**
     * Set soldeLot
     *
     * @param integer $soldeLot
     *
     * @return MvtLot
     */
    public function setSoldeLot($soldeLot)
    {
        $this->soldeLot = $soldeLot;

        return $this;
    }

    /**
     * Get soldeLot
     *
     * @return int
     */
    public function getSoldeLot()
    {
        return $this->soldeLot;
    }

    /**
     * Set dateMvtLot
     *
     * @param \DateTime $dateMvtLot
     *
     * @return MvtLot
     */
    public function setDateMvtLot($dateMvtLot)
    {
        $this->dateMvtLot = $dateMvtLot;

        return $this;
    }

    /**
     * Get dateMvtLot
     *
     * @return \DateTime
     */
    public function getDateMvtLot()
    {
        return $this->dateMvtLot;
    }

    /**
     * Set utilisateur
     *
     * @param \Api\DBBundle\Entity\Utilisateur $utilisateur
     *
     * @return MvtLot
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
     * Set lot
     *
     * @param \Api\DBBundle\Entity\Lot $lot
     *
     * @return MvtLot
     */
    public function setLot(\Api\DBBundle\Entity\Lot $lot = null)
    {
        $this->lot = $lot;

        return $this;
    }

    /**
     * Get lot
     *
     * @return \Api\DBBundle\Entity\Lot
     */
    public function getLot()
    {
        return $this->lot;
    }
    
    /**
     * @ORM\PrePersist
     * 
     */
    public function setCreated()
    {
        $this->dateMvtLot = new \DateTime();
    }

    /**
     * Set addressLivraison
     *
     * @param \Api\DBBundle\Entity\AddressLivraison $addressLivraison
     *
     * @return MvtLot
     */
    public function setAddressLivraison(\Api\DBBundle\Entity\AddressLivraison $addressLivraison = null)
    {
        $this->addressLivraison = $addressLivraison;

        return $this;
    }

    /**
     * Get addressLivraison
     *
     * @return \Api\DBBundle\Entity\AddressLivraison
     */
    public function getAddressLivraison()
    {
        return $this->addressLivraison;
    }
}
