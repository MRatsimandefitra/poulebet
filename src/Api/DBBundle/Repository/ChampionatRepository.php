<?php

namespace Api\DBBundle\Repository;

/**
 * ChampionatRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ChampionatRepository extends \Doctrine\ORM\EntityRepository
{
    public function getChampionatParPays($pays){
        $dql= "SELECT ch, tp from ApiDBBundle:Championat ch
               LEFT JOIN ch.teamsPays tp
                WHERE tp.fullNameChampionat LIKE %:pays%";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('pays', $pays);
        return $query->getResult();
    }

    public function findListPaysWithChampionatWithMatchs()
    {
        $dql = "SELECT m, ch, tp from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                LEFT JOIN ch.teamsPays tp
                WHERE ch.isEnable = true
                ";
        /*CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat*/
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();

    }

    public function findChampionatWithatchByPays($pays)
    {
        $dql = "SELECT ch from ApiDBBundle:Championat ch
                LEFT JOIN ch.teamsPays tp
                WHERE ch.isEnable = true";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    public function findChampionatWithMatchByPays($pays)
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m
               LEFT JOIN m.championat ch
               left JOIN ch.teamsPays tp
               WHERE tp.name LIKE :pays";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('pays', $pays);
        return $query->getResult();
    }

    function findMatchsByChampionat($championat)
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE ch.nomChampionat LIKE :nomChampionat";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('nomChampionat', $championat);
        return $query->getResult();
    }
}
