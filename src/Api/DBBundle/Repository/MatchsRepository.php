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

    /**
     * Ws, récupérer la liste des championnats qui ont des matchs
     */
    function getListChampionatWithMatch()
    {
        $dql = "SELECT DISTINCT m, ch from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat
                GROUP BY ch.nomChampionat";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    /**
     *
     * Ws, récupérer la liste des matchs pour le championnat sélectionné.(tri décroissant).
     * @param $championat
     * @return array
     */
    function getListeMatchsBySelectedChampionat($championat)
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE ch.nomChampionat LIKE :championat
                OR ch.fullNameChampionat LIKE :championat
                ORDER BY ch.fullNameChampionat ASC";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('championat', $championat);
        return $query->getResult();
    }

    /**
     * Ws, récupérer la liste des pays qui ont des championnats nationaux avec des matchs
     */
    function getListePaysWithChampionatWithMatchs()
    {
        $dql = "SELECT m, ch, td, tv from ApiDBBundle:Matchs m
               LEFT JOIN  m.championat ch
               LEFT JOIN  m.teamsVisiteur tv
               LEFT JOIN m.teamsDomicile td
               LEFT JOIN td.teamsPays tpd
               LEFT JOIN tv.teamsPays tpv
               GROUP BY tpd.name ";
        $query = $this->getEntityManager()->createQuery($dql);
        //$query->setParameter('pays', $pays);
        return $query->getResult();
    }

    /**
     * Ws récupérer la liste des championnats nationaux pour le pays sélectionné
     */
    function findListeChampionatNationauxByPays($pays)
    {
        $dql = "SELECT ch, tp from ApiDBBundle:Championat ch
                LEFT JOIN ch.teamsPays tp
                WHERE CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat
                AND tp.name LIKE :pays
                OR tp.fullName LIKE :pays";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('pays', $pays);
        return $query->getResult();

    }
}