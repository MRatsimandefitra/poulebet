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
     * @ORM\Column(name="isPopup", type="boolean", nullable=true)
     */
    private $isPopup;
    /**
     * @var bool
     *
     * @ORM\Column(name="isBannier", type="boolean", nullable=true)
     */
    private $isBannier;
    /**
     * @var bool
     *
     * @ORM\Column(name="isPublish", type="boolean", nullable=true)
     */
    private $isPublish;


    public function __construct(){
        $this->setIsPublish(true);
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

    /**
     * Set isPublish
     *
     * @param boolean $isPublish
     *
     * @return Publicite
     */
    public function setIsPublish($isPublish)
    {
        $this->isPublish = $isPublish;

        return $this;
    }

    /**
     * Get isPublish
     *
     * @return boolean
     */
    public function getIsPublish()
    {
        return $this->isPublish;
    }

    /**
     * Set isPopup
     *
     * @param boolean $isPopup
     *
     * @return Publicite
     */
    public function setIsPopup($isPopup)
    {
        $this->isPopup = $isPopup;

        return $this;
    }

    /**
     * Get isPopup
     *
     * @return boolean
     */
    public function getIsPopup()
    {
        return $this->isPopup;
    }

    /**
     * Set isBannier
     *
     * @param boolean $isBannier
     *
     * @return Publicite
     */
    public function setIsBannier($isBannier)
    {
        $this->isBannier = $isBannier;

        return $this;
    }

    /**
     * Get isBannier
     *
     * @return boolean
     */
    public function getIsBannier()
    {
        return $this->isBannier;
    }
}
