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
}
