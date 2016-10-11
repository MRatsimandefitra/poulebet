<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AddressLivraison
 *
 * @ORM\Table(name="address_livraison")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\AddressLivraisonRepository")
 */
class AddressLivraison
{
    /**
     * @ORM\ManyToOne(targetEntity="Utilisateur", cascade={"persist","remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;
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
     * @ORM\Column(name="pays", type="string", length=255)
     */
    private $pays;

    /**
     * @var string
     *
     * @ORM\Column(name="nomcomplet", type="string", length=255, nullable=true)
     */
    private $nomcomplet;

    /**
     * @var string
     *
     * @ORM\Column(name="voie", type="string", length=255, nullable=true)
     */
    private $voie;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     */
    private $numero;

    /**
     * @var int
     *
     * @ORM\Column(name="codePostal", type="integer", nullable=true)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="region", type="string", length=255, nullable=true)
     */
    private $region;


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
     * Set pays
     *
     * @param string $pays
     *
     * @return AddressLivraison
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }

    /**
     * Set nomcomplet
     *
     * @param string $nomcomplet
     *
     * @return AddressLivraison
     */
    public function setNomcomplet($nomcomplet)
    {
        $this->nomcomplet = $nomcomplet;

        return $this;
    }

    /**
     * Get nomcomplet
     *
     * @return string
     */
    public function getNomcomplet()
    {
        return $this->nomcomplet;
    }

    /**
     * Set voie
     *
     * @param string $voie
     *
     * @return AddressLivraison
     */
    public function setVoie($voie)
    {
        $this->voie = $voie;

        return $this;
    }

    /**
     * Get voie
     *
     * @return string
     */
    public function getVoie()
    {
        return $this->voie;
    }

    /**
     * Set numero
     *
     * @param integer $numero
     *
     * @return AddressLivraison
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return AddressLivraison
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
     * Set ville
     *
     * @param string $ville
     *
     * @return AddressLivraison
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return AddressLivraison
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set user
     *
     * @param \Api\DBBundle\Entity\Utilisateur $user
     *
     * @return AddressLivraison
     */
    public function setUser(\Api\DBBundle\Entity\Utilisateur $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Api\DBBundle\Entity\Utilisateur
     */
    public function getUser()
    {
        return $this->user;
    }
}
