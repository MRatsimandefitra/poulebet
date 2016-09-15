<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 13/09/2016
 * Time: 13:48
 */

namespace Api\CommonBundle\Utils;


class MatchsManager extends ApiManager{

    public function getTotalItemsMatchsByStatus($status = null){
        // getTotal By argument
        $dqlTotal = "SELECT m from ApiDBBundle:Matchs m ";
        if($status){
            $dqlTotal .= " WHERE m.statusMatch LIKE :status";
        }

        $queryTotal = $this->getEm()->createQuery($dqlTotal);
        if($status){
            $queryTotal->setParameter('status', $status);
        }

        $data = $queryTotal->getResult();
        if(count($data)!= 0){
            $result = count($data) + 1;
        }else{
            $result = 0;
        }
        return count($data);
    }

    public function getTotalPronostic(){
        $dqlTotal = "SELECT m from ApiDBBundle:Matchs m
where m.cot1Pronostic is not null or m.cote2Pronostic is not null or m.coteNPronistic is not null
or m.masterProno1 is not null or m.masterPronoN is not null or m.masterProno2 is not null";
        $queryTotal = $this->getEm()->createQuery($dqlTotal);
        $data = $queryTotal->getResult();

        return count($data);
    }

    public function getTotalMatch(){
        $dql = "SELECT m from ApiDBBundle:Matchs m ";
        $query = $this->getEm()->createQuery($dql);
        $data = $query->getResult();
        return count($data);
    }

    public function getCountry(){
        $dqli = "SELECT ch From ApiDBBundle:championat ch where ch.pays  is not null ";
        $query = $this->getEm()->createQuery($dqli);
        $country = $query->getResult();
        return $country;
    }
}