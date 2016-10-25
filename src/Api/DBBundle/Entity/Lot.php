<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Lot
 *
 * @ORM\Table(name="lot")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\LotRepository")
 */
class Lot
{
    /**
     * @ORM\ManyToOne(targetEntity="LotCategory", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $lotCategory;

    /**
     * @ORM\ManyToOne(targetEntity="Concours", cascade={"persist"})
     * @ORM\JoinColumn(name="concours_id")
     */
    private $concours;

    /**
     * @ORM\OneToMany(targetEntity="MvtLot", mappedBy="lot")
     */
    private $mvtLots;
    
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
     * @ORM\Column(name="nomLot", type="string", length=100)
     */
    private $nomLot;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPointNecessaire", type="integer")
     */
    private $nbPointNecessaire;

    /**
     * @var int
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cheminImage", type="string", length=255)
     */
    private $cheminImage;

    /**
     * @var int
     *
     * @ORM\Column(name="createdAt", type="date")
     */
    private $createdAt;
    
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
     * Set nomLot
     *
     * @param string $nomLot
     *
     * @return Lot
     */
    public function setNomLot($nomLot)
    {
        $this->nomLot = $nomLot;

        return $this;
    }

    /**
     * Get nomLot
     *
     * @return string
     */
    public function getNomLot()
    {
        return $this->nomLot;
    }

    /**
     * Set nbPointNecessaire
     *
     * @param integer $nbPointNecessaire
     *
     * @return Lot
     */
    public function setNbPointNecessaire($nbPointNecessaire)
    {
        $this->nbPointNecessaire = $nbPointNecessaire;

        return $this;
    }

    /**
     * Get nbPointNecessaire
     *
     * @return int
     */
    public function getNbPointNecessaire()
    {
        return $this->nbPointNecessaire;
    }

    /**
     * Set cheminImage
     *
     * @param string $cheminImage
     *
     * @return Lot
     */
    public function setCheminImage($cheminImage)
    {
        if(!empty($cheminImage)){
            $this->cheminImage = $cheminImage;  
        }

        return $this;
    }

    /**
     * Get cheminImage
     *
     * @return string
     */
    public function getCheminImage()
    {
        return $this->cheminImage;
    }

    /**
     * Set concours
     *
     * @param \Api\DBBundle\Entity\Concours $concours
     *
     * @return Lot
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
     * Set description
     *
     * @param string $description
     *
     * @return Lot
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Lot
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set lotCategory
     *
     * @param \Api\DBBundle\Entity\LotCategory $lotCategory
     *
     * @return Lot
     */
    public function setLotCategory(\Api\DBBundle\Entity\LotCategory $lotCategory = null)
    {
        $this->lotCategory = $lotCategory;

        return $this;
    }

    /**
     * Get lotCategory
     *
     * @return \Api\DBBundle\Entity\LotCategory
     */
    public function getLotCategory()
    {
        return $this->lotCategory;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->mvtLots = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add mvtLot
     *
     * @param \Api\DBBundle\Entity\MvtLot $mvtLot
     *
     * @return Lot
     */
    public function addMvtLot(\Api\DBBundle\Entity\MvtLot $mvtLot)
    {
        $this->mvtLots[] = $mvtLot;

        return $this;
    }

    /**
     * Remove mvtLot
     *
     * @param \Api\DBBundle\Entity\MvtLot $mvtLot
     */
    public function removeMvtLot(\Api\DBBundle\Entity\MvtLot $mvtLot)
    {
        $this->mvtLots->removeElement($mvtLot);
    }

    /**
     * Get mvtLots
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMvtLots()
    {
        return $this->mvtLots;
    }
    
    /**
     * Get last quantity
     * 
     * @return int
     */
    public function getQuantity(){
        $mvtLots = array();
        foreach($this->getMvtLots() as $mvt){
            $mvtLots[$mvt->getId()] = $mvt->getSoldeLot();
        }
        reset($mvtLots);
        return end($mvtLots);
    }
}
