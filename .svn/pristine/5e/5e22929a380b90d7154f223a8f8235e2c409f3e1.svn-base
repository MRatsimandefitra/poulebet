<?php

namespace Ws\RestBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
