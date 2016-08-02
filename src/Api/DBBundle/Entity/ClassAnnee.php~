<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassAnnee
 *
 * @ORM\Table(name="class_annee")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ClassAnneeRepository")
 */
class ClassAnnee
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
     * @ORM\Column(name="nbMatchsJoues", type="integer")
     */
    private $nbMatchsJoues;

    /**
     * @var int
     *
     * @ORM\Column(name="nbMatchGagnes", type="integer")
     */
    private $nbMatchGagnes;


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
     * @return ClassAnnee
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
     * @return ClassAnnee
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
     * @return ClassAnnee
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
     * Set nbMatchGagnes
     *
     * @param integer $nbMatchGagnes
     *
     * @return ClassAnnee
     */
    public function setNbMatchGagnes($nbMatchGagnes)
    {
        $this->nbMatchGagnes = $nbMatchGagnes;

        return $this;
    }

    /**
     * Get nbMatchGagnes
     *
     * @return int
     */
    public function getNbMatchGagnes()
    {
        return $this->nbMatchGagnes;
    }
}
