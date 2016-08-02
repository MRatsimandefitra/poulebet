<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassEm
 *
 * @ORM\Table(name="class_em")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ClassEmRepository")
 */
class ClassEm
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
     * @ORM\Column(name="numeroSemaine", type="integer")
     */
    private $numeroSemaine;

    /**
     * @var string
     *
     * @ORM\Column(name="libelle", type="string", length=100)
     */
    private $libelle;

    /**
     * @var float
     *
     * @ORM\Column(name="nbPointsClass", type="float")
     */
    private $nbPointsClass;

    /**
     * @var int
     *
     * @ORM\Column(name="nbMatchsJoues", type="integer")
     */
    private $nbMatchsJoues;

    /**
     * @var int
     *
     * @ORM\Column(name="nbMatchsGagnes", type="integer")
     */
    private $nbMatchsGagnes;


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
     * Set numeroSemaine
     *
     * @param integer $numeroSemaine
     *
     * @return ClassEm
     */
    public function setNumeroSemaine($numeroSemaine)
    {
        $this->numeroSemaine = $numeroSemaine;

        return $this;
    }

    /**
     * Get numeroSemaine
     *
     * @return int
     */
    public function getNumeroSemaine()
    {
        return $this->numeroSemaine;
    }

    /**
     * Set libelle
     *
     * @param string $libelle
     *
     * @return ClassEm
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set nbPointsClass
     *
     * @param float $nbPointsClass
     *
     * @return ClassEm
     */
    public function setNbPointsClass($nbPointsClass)
    {
        $this->nbPointsClass = $nbPointsClass;

        return $this;
    }

    /**
     * Get nbPointsClass
     *
     * @return float
     */
    public function getNbPointsClass()
    {
        return $this->nbPointsClass;
    }

    /**
     * Set nbMatchsJoues
     *
     * @param integer $nbMatchsJoues
     *
     * @return ClassEm
     */
    public function setNbMatchsJoues($nbMatchsJoues)
    {
        $this->nbMatchsJoues = $nbMatchsJoues;

        return $this;
    }

    /**
     * Get nbMatchsJoues
     *
     * @return int
     */
    public function getNbMatchsJoues()
    {
        return $this->nbMatchsJoues;
    }

    /**
     * Set nbMatchsGagnes
     *
     * @param integer $nbMatchsGagnes
     *
     * @return ClassEm
     */
    public function setNbMatchsGagnes($nbMatchsGagnes)
    {
        $this->nbMatchsGagnes = $nbMatchsGagnes;

        return $this;
    }

    /**
     * Get nbMatchsGagnes
     *
     * @return int
     */
    public function getNbMatchsGagnes()
    {
        return $this->nbMatchsGagnes;
    }
}
