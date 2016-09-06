<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LotoFoot
 *
 * @ORM\Table(name="loto_foot")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\LotoFootRepository")
 */
class LotoFoot
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
     * @ORM\Column(name="finValidation", type="date")
     */
    private $finValidation;
    /**
     * @var string
     *
     * @ORM\Column(name="typeLotoFoot", type="string", length=255)
     */
    private $typeLotoFoot;


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
     * @return LotoFoot
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
     * @return LotoFoot
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
     * Set typeLotoFoot
     *
     * @param string $typeLotoFoot
     *
     * @return LotoFoot
     */
    public function setTypeLotoFoot($typeLotoFoot)
    {
        $this->typeLotoFoot = $typeLotoFoot;

        return $this;
    }

    /**
     * Get typeLotoFoot
     *
     * @return string
     */
    public function getTypeLotoFoot()
    {
        return $this->typeLotoFoot;
    }
}
