<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 23/08/2016
 * Time: 09:59
 */

namespace Api\CommonBundle\Utils;

use Symfony\Component\DependencyInjection\Container;

class Roles {

    private $container;
    private $em;
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    public function __construct(Container $container){

        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
    }

    public function getDroitAdmin($droit){
        //var_dump($this->getCurrentUser()); die;
        $entity =  $this->em->getRepository(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getCurrentUser(), 'droit' => $this->getCurrentDroit($droit)));
        return $entity;
    }
    private function getCurrentUser(){
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }
    private function getCurrentDroit($droit){
        return $this->em->getRepository(self::ENTITY_DROIT)->findOneBy(array('fonctionnalite' => $droit));
    }
}