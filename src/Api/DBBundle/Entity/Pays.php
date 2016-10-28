<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Pays
 *
 * @ORM\Table(name="pays")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\PaysRepository")
 */
class Pays
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
     * @ORM\Column(name="nomPays", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nomPays;

    /**
     * @ORM\OneToMany(targetEntity="Region", mappedBy="pays")
     */
    private $regions;
    
    /**
     * @ORM\OneToMany(targetEntity="AddressLivraison", mappedBy="pays")
     */
    private $addressLivraisons;
    
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
     * Set nomPays
     *
     * @param string $nomPays
     *
     * @return Pays
     */
    public function setNomPays($nomPays)
    {
        $this->nomPays = $nomPays;

        return $this;
    }

    /**
     * Get nomPays
     *
     * @return string
     */
    public function getNomPays()
    {
        return $this->nomPays;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->regions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->addressLivraisons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add region
     *
     * @param \Api\DBBundle\Entity\Region $region
     *
     * @return Pays
     */
    public function addRegion(\Api\DBBundle\Entity\Region $region)
    {
        $this->regions[] = $region;

        return $this;
    }

    /**
     * Remove region
     *
     * @param \Api\DBBundle\Entity\Region $region
     */
    public function removeRegion(\Api\DBBundle\Entity\Region $region)
    {
        $this->regions->removeElement($region);
    }

    /**
     * Get regions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRegions()
    {
        return $this->regions;
    }
    
    public function __toString() {
        return $this->nomPays;
    }

    /**
     * Add addressLivraison
     *
     * @param \Api\DBBundle\Entity\AddressLivraison $addressLivraison
     *
     * @return Pays
     */
    public function addAddressLivraison(\Api\DBBundle\Entity\AddressLivraison $addressLivraison)
    {
        $this->addressLivraisons[] = $addressLivraison;

        return $this;
    }

    /**
     * Remove addressLivraison
     *
     * @param \Api\DBBundle\Entity\AddressLivraison $addressLivraison
     */
    public function removeAddressLivraison(\Api\DBBundle\Entity\AddressLivraison $addressLivraison)
    {
        $this->addressLivraisons->removeElement($addressLivraison);
    }

    /**
     * Get addressLivraisons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddressLivraisons()
    {
        return $this->addressLivraisons;
    }
}
