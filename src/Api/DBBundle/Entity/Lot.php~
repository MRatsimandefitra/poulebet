<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Lot
 *
 * @ORM\Table(name="lot")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\LotRepository")
 */
class Lot
{
    /**
     * @ORM\ManyToOne(targetEntity="Concours", cascade={"persist"})
     * @ORM\JoinColumn(name="concours_id")
     */
    private $concours;

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
     * @ORM\Column(name="nomLot", type="string", length=100)
     */
    private $nomLot;

    /**
     * @var int
     *
     * @ORM\Column(name="nbPointNecessaire", type="integer")
     */
    private $nbPointNecessaire;

    /**
     * @var string
     *
     * @ORM\Column(name="cheminImage", type="string", length=255)
     */
    private $cheminImage;


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
     * Set nomLot
     *
     * @param string $nomLot
     *
     * @return Lot
     */
    public function setNomLot($nomLot)
    {
        $this->nomLot = $nomLot;

        return $this;
    }

    /**
     * Get nomLot
     *
     * @return string
     */
    public function getNomLot()
    {
        return $this->nomLot;
    }

    /**
     * Set nbPointNecessaire
     *
     * @param integer $nbPointNecessaire
     *
     * @return Lot
     */
    public function setNbPointNecessaire($nbPointNecessaire)
    {
        $this->nbPointNecessaire = $nbPointNecessaire;

        return $this;
    }

    /**
     * Get nbPointNecessaire
     *
     * @return int
     */
    public function getNbPointNecessaire()
    {
        return $this->nbPointNecessaire;
    }

    /**
     * Set cheminImage
     *
     * @param string $cheminImage
     *
     * @return Lot
     */
    public function setCheminImage($cheminImage)
    {
        $this->cheminImage = $cheminImage;

        return $this;
    }

    /**
     * Get cheminImage
     *
     * @return string
     */
    public function getCheminImage()
    {
        return $this->cheminImage;
    }

    /**
     * Set concours
     *
     * @param \Api\DBBundle\Entity\Concours $concours
     *
     * @return Lot
     */
    public function setConcours(\Api\DBBundle\Entity\Concours $concours = null)
    {
        $this->concours = $concours;

        return $this;
    }

    /**
     * Get concours
     *
     * @return \Api\DBBundle\Entity\Concours
     */
    public function getConcours()
    {
        return $this->concours;
    }
}
