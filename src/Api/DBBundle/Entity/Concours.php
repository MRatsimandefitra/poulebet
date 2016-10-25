<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Concours
 *
 * @ORM\Table(name="concours")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ConcoursRepository")
 */
class Concours
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
     * @var int
     *
     * @ORM\Column(name="numero", type="integer")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="nomConcours", type="string", length=100)
     */
    private $nomConcours;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebut", type="datetime")
     */
    private $dateDebut;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFinale", type="datetime")
     */
    private $dateFinale;
    /**
     * @ORM\ManyToMany(targetEntity="Matchs" , cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     **/
    /*private $matchs;*/

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
     * Set numero
     *
     * @param integer $numero
     *
     * @return Concours
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
     * Set nomConcours
     *
     * @param string $nomConcours
     *
     * @return Concours
     */
    public function setNomConcours($nomConcours)
    {
        $this->nomConcours = $nomConcours;

        return $this;
    }

    /**
     * Get nomConcours
     *
     * @return string
     */
    public function getNomConcours()
    {
        return $this->nomConcours;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matchs = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Set dateDebut
     *
     * @param \DateTime $dateDebut
     *
     * @return Concours
     */
    public function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    /**
     * Get dateDebut
     *
     * @return \DateTime
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * Set dateFinale
     *
     * @param \DateTime $dateFinale
     *
     * @return Concours
     */
    public function setDateFinale($dateFinale)
    {
        $this->dateFinale = $dateFinale;

        return $this;
    }

    /**
     * Get dateFinale
     *
     * @return \DateTime
     */
    public function getDateFinale()
    {
        return $this->dateFinale;
    }
}
