<?php

namespace Ws\RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Api\DBBundle\Entity\Utilisateur;

class ApiRestController extends FOSRestController
{

    public function __construct()
    {
    }

    public function insert($data)
    {
        try {
            $this->getEm()->persist($data);
            $this->getEm()->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function getEm()
    {
        return $this->get('doctrine.orm.entity_manager');
    }
    protected function encodePassword($password){
        return md5($password);
    }
    protected function generateToken($userToken){
        return "".time()."_".$userToken."_".(time() + (1 * 24 * 60 * 60));
    }
    protected function isExpiredToken($token){
        $res = split('_', $token);
        $time = intval($res[2]);
        if ($time < time()){
            return true;
        }
        return false;
    }
    protected function generatePassword($length = 5){
        return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
        
    }
    protected function sendEmail($message){
        $res = $this->get('mailer')->send($message);
        return true;
    }
    
}
