<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Championat
 *
 * @ORM\Table(name="championat")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ChampionatRepository")
 */
class Championat
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
     * @ORM\Column(name="nomChampionat", type="string", length=255)
     */
    private $nomChampionat;


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
     * Set nomChampionat
     *
     * @param string $nomChampionat
     *
     * @return Championat
     */
    public function setNomChampionat($nomChampionat)
    {
        $this->nomChampionat = $nomChampionat;

        return $this;
    }

    /**
     * Get nomChampionat
     *
     * @return string
     */
    public function getNomChampionat()
    {
        return $this->nomChampionat;
    }
}
