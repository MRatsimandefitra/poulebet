<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Facebook
 *
 * @ORM\Table(name="facebook")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\FacebookRepository")
 */
class Facebook
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
     * @ORM\Column(name="imageOeuf", type="string", length=255)
     */
    private $imageOeuf;

    /**
     * @var string
     *
     * @ORM\Column(name="imagePoulebet", type="string", length=255)
     */
    private $imagePoulebet;


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
     * Set imageOeuf
     *
     * @param string $imageOeuf
     *
     * @return Facebook
     */
    public function setImageOeuf($imageOeuf)
    {
        $this->imageOeuf = $imageOeuf;

        return $this;
    }

    /**
     * Get imageOeuf
     *
     * @return string
     */
    public function getImageOeuf()
    {
        return $this->imageOeuf;
    }

    /**
     * Set imagePoulebet
     *
     * @param string $imagePoulebet
     *
     * @return Facebook
     */
    public function setImagePoulebet($imagePoulebet)
    {
        $this->imagePoulebet = $imagePoulebet;

        return $this;
    }

    /**
     * Get imagePoulebet
     *
     * @return string
     */
    public function getImagePoulebet()
    {
        return $this->imagePoulebet;
    }
}

