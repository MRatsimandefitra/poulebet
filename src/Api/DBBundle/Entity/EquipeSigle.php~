<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EquipeSigle
 *
 * @ORM\Table(name="equipe_sigle")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\EquipeSigleRepository")
 */
class EquipeSigle
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
     * @ORM\Column(name="cheminLogoDomicile", type="string", length=255)
     */
    private $cheminLogoDomicile;


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
     * Set cheminLogoDomicile
     *
     * @param string $cheminLogoDomicile
     *
     * @return EquipeSigle
     */
    public function setCheminLogoDomicile($cheminLogoDomicile)
    {
        $this->cheminLogoDomicile = $cheminLogoDomicile;

        return $this;
    }

    /**
     * Get cheminLogoDomicile
     *
     * @return string
     */
    public function getCheminLogoDomicile()
    {
        return $this->cheminLogoDomicile;
    }
}
