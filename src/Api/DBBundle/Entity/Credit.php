<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Credit
 *
 * @ORM\Table(name="credit")
 * @ORM\Entity(repositoryClass="Api\DBBundle\Repository\CreditRepository")
 */
class Credit
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
     * @var int
     *
     * @ORM\Column(name="typeCredit", type="integer")
     */
    private $typeCredit;


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
     * Set typeCredit
     *
     * @param integer $typeCredit
     *
     * @return Credit
     */
    public function setTypeCredit($typeCredit)
    {
        $this->typeCredit = $typeCredit;

        return $this;
    }

    /**
     * Get typeCredit
     *
     * @return int
     */
    public function getTypeCredit()
    {
        return $this->typeCredit;
    }
}
