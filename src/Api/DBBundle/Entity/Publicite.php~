<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Publicite
 *
 * @ORM\Table(name="publicite")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\PubliciteRepository")
 */
class Publicite
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
     * @ORM\Column(name="cheminPub", type="string", length=255)
     */
    private $cheminPub;

    /**
     * @var bool
     *
     * @ORM\Column(name="type", type="boolean")
     */
    private $type;


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
     * Set cheminPub
     *
     * @param string $cheminPub
     *
     * @return Publicite
     */
    public function setCheminPub($cheminPub)
    {
        $this->cheminPub = $cheminPub;

        return $this;
    }

    /**
     * Get cheminPub
     *
     * @return string
     */
    public function getCheminPub()
    {
        return $this->cheminPub;
    }

    /**
     * Set type
     *
     * @param boolean $type
     *
     * @return Publicite
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return bool
     */
    public function getType()
    {
        return $this->type;
    }
}
