<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Oeufs
 *
 * @ORM\Table(name="oeufs")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\OeufsRepository")
 */
class Oeufs
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
     * @var String
     *
     * @ORM\Column(name="productSKU", type="string", length=255, nullable=true)
     */
    private $productSKU;
    /**
     * @var float
     *
     * @ORM\Column(name="tarifEuro", type="float")
     */
    private $tarifEuro;

    /**
     * @var int
     *
     * @ORM\Column(name="tarifOeufs", type="integer")
     */
    private $tarifOeufs;

    /**
     * @var string
     *
     * @ORM\Column(name="imageOeuf", type="string", length=255, nullable=true)
     */
    private $imageOeuf;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime")
     */
    private $updatedAt;

    function __construct()
    {
        $this->setUpdatedAt(new \DateTime('now'));
    }


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
     * Set tarifEuro
     *
     * @param float $tarifEuro
     *
     * @return Oeufs
     */
    public function setTarifEuro($tarifEuro)
    {
        $this->tarifEuro = $tarifEuro;

        return $this;
    }

    /**
     * Get tarifEuro
     *
     * @return float
     */
    public function getTarifEuro()
    {
        return $this->tarifEuro;
    }

    /**
     * Set tarifOeufs
     *
     * @param integer $tarifOeufs
     *
     * @return Oeufs
     */
    public function setTarifOeufs($tarifOeufs)
    {
        $this->tarifOeufs = $tarifOeufs;

        return $this;
    }

    /**
     * Get tarifOeufs
     *
     * @return int
     */
    public function getTarifOeufs()
    {
        return $this->tarifOeufs;
    }

    /**
     * Set imageOeuf
     *
     * @param string $imageOeuf
     *
     * @return Oeufs
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Oeufs
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set productSKU
     *
     * @param string $productSKU
     *
     * @return Oeufs
     */
    public function setProductSKU($productSKU)
    {
        $this->productSKU = $productSKU;

        return $this;
    }

    /**
     * Get productSKU
     *
     * @return string
     */
    public function getProductSKU()
    {
        return $this->productSKU;
    }
}
