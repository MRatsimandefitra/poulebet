<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Region
 *
 * @ORM\Table(name="region")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\RegionRepository")
 */
class Region
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
     * @ORM\Column(name="nom", type="string", length=100)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Pays", inversedBy="regions")
     * @ORM\JoinColumn(name="pays_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $pays;
    
    /**
     * @ORM\OneToMany(targetEntity="AddressLivraison", mappedBy="region")
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Region
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set pays
     *
     * @param \Api\DBBundle\Entity\Pays $pays
     *
     * @return Region
     */
    public function setPays(\Api\DBBundle\Entity\Pays $pays = null)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return \Api\DBBundle\Entity\Pays
     */
    public function getPays()
    {
        return $this->pays;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->addressLivraisons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add addressLivraison
     *
     * @param \Api\DBBundle\Entity\AddressLivraison $addressLivraison
     *
     * @return Region
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
    
    public function __toString() {
        return $this->nom;
    }
}
