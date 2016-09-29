<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Connected
 *
 * @ORM\Table(name="connected")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\ConnectedRepository")
 */
class Connected
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
     * @ORM\Column(name="tokenSession", type="string", length=255)
     */
    private $tokenSession;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;


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
     * Set tokenSession
     *
     * @param string $tokenSession
     *
     * @return Connected
     */
    public function setTokenSession($tokenSession)
    {
        $this->tokenSession = $tokenSession;

        return $this;
    }

    /**
     * Get tokenSession
     *
     * @return string
     */
    public function getTokenSession()
    {
        return $this->tokenSession;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Connected
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }
}

