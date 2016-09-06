<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiKey
 *
 * @ORM\Table(name="api_key")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ApiKeyRepository")
 */
class ApiKey
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
     * @ORM\Column(name="apikey", type="string", length=255, nullable=true)
     */
    private $apikey;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDebutValide", type="date", nullable=true)
     */
    private $dateDebutValide;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateFinaleValide", type="date", nullable=true)
     */
    private $dateFinaleValide;


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
     * Set apikey
     *
     * @param string $apikey
     *
     * @return ApiKey
     */
    public function setApikey($apikey)
    {
        $this->apikey = $apikey;

        return $this;
    }

    /**
     * Get apikey
     *
     * @return string
     */
    public function getApikey()
    {
        return $this->apikey;
    }

    /**
     * Set dateDebutValide
     *
     * @param \DateTime $dateDebutValide
     *
     * @return ApiKey
     */
    public function setDateDebutValide($dateDebutValide)
    {
        $this->dateDebutValide = $dateDebutValide;

        return $this;
    }

    /**
     * Get dateDebutValide
     *
     * @return \DateTime
     */
    public function getDateDebutValide()
    {
        return $this->dateDebutValide;
    }

    /**
     * Set dateFinaleValide
     *
     * @param \DateTime $dateFinaleValide
     *
     * @return ApiKey
     */
    public function setDateFinaleValide($dateFinaleValide)
    {
        $this->dateFinaleValide = $dateFinaleValide;

        return $this;
    }

    /**
     * Get dateFinaleValide
     *
     * @return \DateTime
     */
    public function getDateFinaleValide()
    {
        return $this->dateFinaleValide;
    }
}
