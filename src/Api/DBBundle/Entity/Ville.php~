<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ville
 *
 * @ORM\Table(name="ville")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\VilleRepository")
 */
class Ville
{
    /**
     * @ORM\ManyToOne(targetEntity="Pays", cascade={"persist"})
     * @ORM\JoinColumn(name="pays_id")
     */
    private $pays;
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
     * @ORM\Column(name="nomVille", type="string", length=255)
     */
    private $nomVille;

    /**
     * @var int
     *
     * @ORM\Column(name="codePostal", type="integer")
     */
    private $codePostal;


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
     * Set nomVille
     *
     * @param string $nomVille
     *
     * @return Ville
     */
    public function setNomVille($nomVille)
    {
        $this->nomVille = $nomVille;

        return $this;
    }

    /**
     * Get nomVille
     *
     * @return string
     */
    public function getNomVille()
    {
        return $this->nomVille;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Ville
     */
    public function setCodePostal($codePostal)
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return int
     */
    public function getCodePostal()
    {
        return $this->codePostal;
    }

    /**
     * Set pays
     *
     * @param \Api\DBBundle\Entity\Pays $pays
     *
     * @return Ville
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
