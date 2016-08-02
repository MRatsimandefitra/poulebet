<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassMois
 *
 * @ORM\Table(name="class_mois")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ClassMoisRepository")
 */
class ClassMois
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
     * @ORM\Column(name="nbMatachsJoues", type="integer")
     */
    private $nbMatachsJoues;

    /**
     * @var int
     *
     * @ORM\Column(name="nbMathsGagnes", type="integer")
     */
    private $nbMathsGagnes;


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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return ClassMois
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
     * @return ClassMois
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
     * Set nbMatachsJoues
     *
     * @param integer $nbMatachsJoues
     *
     * @return ClassMois
     */
    public function setNbMatachsJoues($nbMatachsJoues)
    {
        $this->nbMatachsJoues = $nbMatachsJoues;

        return $this;
    }

    /**
     * Get nbMatachsJoues
     *
     * @return int
     */
    public function getNbMatachsJoues()
    {
        return $this->nbMatachsJoues;
    }

    /**
     * Set nbMathsGagnes
     *
     * @param integer $nbMathsGagnes
     *
     * @return ClassMois
     */
    public function setNbMathsGagnes($nbMathsGagnes)
    {
        $this->nbMathsGagnes = $nbMathsGagnes;

        return $this;
    }

    /**
     * Get nbMathsGagnes
     *
     * @return int
     */
    public function getNbMathsGagnes()
    {
        return $this->nbMathsGagnes;
    }
}
