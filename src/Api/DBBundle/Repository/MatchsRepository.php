<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 19/08/2016
 * Time: 17:03
 */

namespace Api\DBBundle\Repository;


class MatchsRepository extends \Doctrine\ORM\EntityRepository
{


    public function getCurrentMatchs()
    {
        /*$dql = "SELECT m fromApiDBBundle:Matchs m
                LEFT JOIN m."*/
    }

    public function findMatchById($id)
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m WHERE m.id = :id ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('id', $id);
        return $query->getResult();
    }

    function findChampionat($title)
    {
        $dql = "SELECT ch from ApiDBBundle:Championat ch WHERE ch.nomChampionat = :title ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('title', $title);
        return $query->getResult();
    }

    function findChampionatWithMatch(){
        $dql = "SELECT m, c from ApiDBBundle:Matchs m
                LEFT JOIN m.championat c";
        $query  = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    function findChampionatWitwMatchValide(){
        $dql = "SELECT m,ch from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }
    function findMatchsByChampionnat($title){
        
        $dql = "SELECT m,ch FROM ApiDBBundle:Matchs m"
                . " INNER JOIN m.championat ch"
                . " WHERE ch.nomChampionat = :title"
                . " ORDER BY m.dateMatch desc"
                ;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter("title", $title);
        return $query->getArrayResult();
    }

    function findMatchsByChampionnat($title){
        
        $dql = "SELECT m,ch FROM ApiDBBundle:Matchs m"
                . " INNER JOIN m.championat ch"
                . " WHERE ch.nomChampionat = :title"
                . " ORDER BY m.dateMatch desc"
                ;
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter("title", $title);
        return $query->getArrayResult();
    }

}