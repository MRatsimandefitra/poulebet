<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 03/08/2016
 * Time: 17:41
 */

namespace Api\DBBundle\Request;


class Email {

    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

}