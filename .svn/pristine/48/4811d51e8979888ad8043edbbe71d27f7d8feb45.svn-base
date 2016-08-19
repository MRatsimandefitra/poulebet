<?php

namespace Api\DBBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

const TYPE0 = "crédits gratuits à jouer  chaque jour ";
const TYPE1 = "pack 1 credit ";
const TYPE3 = "pack 3 credits ";
const TYPE5 = "pack 5 credits ";
const TYPE10 = "pack 10 credits ";
const TYPE20 = "pack 20 credits ";
const TYPE21 = "crédit 1 partage de l'app sur facebook ";

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
    
    public function __toString() {
        $type = 0;
        switch($this->getTypeCredit()){
            case 0: $type = TYPE0;break;
            case 1: $type = TYPE1;break;
            case 3: $type = TYPE3;break;
            case 5: $type = TYPE5;break;
            case 10: $type = TYPE10;break;
            case 20: $type = TYPE20;break;
            case 21: $type = TYPE21;break;
        }
        return $type;
    }
}
