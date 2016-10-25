<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Droit
 *
 * @ORM\Table(name="droit")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\DroitRepository")
 */
class Droit
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
     * @ORM\Column(name="fonctionnalite", type="string", length=255)
     */
    private $fonctionnalite;


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
     * Set fonctionnalite
     *
     * @param string $fonctionnalite
     *
     * @return Droit
     */
    public function setFonctionnalite($fonctionnalite)
    {
        $this->fonctionnalite = $fonctionnalite;

        return $this;
    }

    /**
     * Get fonctionnalite
     *
     * @return string
     */
    public function getFonctionnalite()
    {
        return $this->fonctionnalite;
    }
}
