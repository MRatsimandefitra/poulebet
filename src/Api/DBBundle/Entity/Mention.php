<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mention
 *
 * @ORM\Table(name="mention")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\MentionRepository")
 */
class Mention
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
     * @ORM\Column(name="mentionLegale", type="text", nullable=true)
     */
    private $mentionLegale;

    /**
     * @var string
     *
     * @ORM\Column(name="cgv", type="text", nullable=true)
     */
    private $cgv;

    /**
     * @var string
     *
     * @ORM\Column(name="cgps", type="text", nullable=true)
     */
    private $cgps;

    /**
     * @var string
     *
     * @ORM\Column(name="cgu", type="text", nullable=true)
     */
    private $cgu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;


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
     * Set mentionLegale
     *
     * @param string $mentionLegale
     *
     * @return Mention
     */
    public function setMentionLegale($mentionLegale)
    {
        $this->mentionLegale = $mentionLegale;

        return $this;
    }

    /**
     * Get mentionLegale
     *
     * @return string
     */
    public function getMentionLegale()
    {
        return $this->mentionLegale;
    }

    /**
     * Set cgv
     *
     * @param string $cgv
     *
     * @return Mention
     */
    public function setCgv($cgv)
    {
        $this->cgv = $cgv;

        return $this;
    }

    /**
     * Get cgv
     *
     * @return string
     */
    public function getCgv()
    {
        return $this->cgv;
    }

    /**
     * Set cgps
     *
     * @param string $cgps
     *
     * @return Mention
     */
    public function setCgps($cgps)
    {
        $this->cgps = $cgps;

        return $this;
    }

    /**
     * Get cgps
     *
     * @return string
     */
    public function getCgps()
    {
        return $this->cgps;
    }

    /**
     * Set cgu
     *
     * @param string $cgu
     *
     * @return Mention
     */
    public function setCgu($cgu)
    {
        $this->cgu = $cgu;

        return $this;
    }

    /**
     * Get cgu
     *
     * @return string
     */
    public function getCgu()
    {
        return $this->cgu;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Mention
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}
