<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 03/03/2016
 * Time: 09:31
 */

namespace Back\SignupBundle\Request;

use Symfony\Component\Validator\Constraints as Assert;

class Login
{

    /**
     * @var $username
     * @Assert\NotBlank(message = "login.notblank" )
     * @Assert\Email(message = "login.validate" )
     *
     */
    private $username;

    /**
     * @var $password
     * @Assert\NotBlank(message="password.notblank")
     *
     */
    private $password;

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }


}