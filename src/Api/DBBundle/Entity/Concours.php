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
     * @ORM\Column(name="finValidation", type="datetime")
     */
    private $finValidation;



    /**
     * @ORM\ManyToMany(targetEntity="Matchs" , cascade={"persist", "remove"})
     *
     **/
    private $matchs;
    
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
     * Set finValidation
     *
     * @param \DateTime $finValidation
     *
     * @return Concours
     */
    public function setFinValidation($finValidation)
    {
        $this->finValidation = $finValidation;

        return $this;
    }

    /**
     * Get finValidation
     *
     * @return \DateTime
     */
    public function getFinValidation()
    {
        return $this->finValidation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->matchs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add match
     *
     * @param \Api\DBBundle\Entity\Matchs $match
     *
     * @return Concours
     */
    public function addMatch(\Api\DBBundle\Entity\Matchs $match)
    {
        $this->matchs[] = $match;

        return $this;
    }

    /**
     * Remove match
     *
     * @param \Api\DBBundle\Entity\Matchs $match
     */
    public function removeMatch(\Api\DBBundle\Entity\Matchs $match)
    {
        $this->matchs->removeElement($match);
    }

    /**
     * Get matchs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMatchs()
    {
        return $this->matchs;
    }
}
