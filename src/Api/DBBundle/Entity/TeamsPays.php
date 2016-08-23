<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TeamsPays
 *
 * @ORM\Table(name="teams_pays")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\TeamsPaysRepository")
 */
class TeamsPays
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
     * @ORM\Column(name="codePays", type="string", length=50, unique=true)
     */
    private $codePays;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;

    /**
     * @var int
     *
     * @ORM\Column(name="codeNumericPays", type="integer",nullable=true)
     */
    private $codeNumericPays;
    /**
     * @var int
     *
     * @ORM\Column(name="logo", type="string",length=255, nullable=true)
     */
    private $logo;


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
     * Set codePays
     *
     * @param string $codePays
     *
     * @return TeamsPays
     */
    public function setCodePays($codePays)
    {
        $this->codePays = $codePays;

        return $this;
    }

    /**
     * Get codePays
     *
     * @return string
     */
    public function getCodePays()
    {
        return $this->codePays;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return TeamsPays
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return TeamsPays
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set codeNumericPays
     *
     * @param integer $codeNumericPays
     *
     * @return TeamsPays
     */
    public function setCodeNumericPays($codeNumericPays)
    {
        $this->codeNumericPays = $codeNumericPays;

        return $this;
    }

    /**
     * Get codeNumericPays
     *
     * @return int
     */
    public function getCodeNumericPays()
    {
        return $this->codeNumericPays;
    }

    /**
     * Set logo
     *
     * @param string $logo
     *
     * @return TeamsPays
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
