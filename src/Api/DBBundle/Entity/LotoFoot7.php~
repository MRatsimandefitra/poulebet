<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LotoFoot7
 *
 * @ORM\Table(name="loto_foot7")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\LotoFoot7Repository")
 */
class LotoFoot7
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
     * @var \DateTime
     *
     * @ORM\Column(name="finValidation", type="datetime")
     */
    private $finValidation;
    


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
     * @return LotoFoot7
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
     * Set finValidation
     *
     * @param \DateTime $finValidation
     *
     * @return LotoFoot7
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
     * @return LotoFoot7
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
