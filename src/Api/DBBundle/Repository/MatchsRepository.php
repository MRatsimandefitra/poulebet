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
    function getListeMatchsBySelectedChampionat($championat, $date = null)
    {
        // a verifier
        if(!$date){
            $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE m.dateMatch BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), 7, 'day')
                AND ((m.masterProno1 is not null and m.masterProno1 = true) or (m.masterProno2 is not null and m.masterProno2 = true) or (m.masterPronoN is not null and m.masterPronoN = true))
                AND ch.nomChampionat LIKE :championat
                ORDER BY m.dateMatch ASC";
            //AND (m.masterProno1 is not null or m.masterProno2 is not null or m.masterPronoN is not null)
        }else{

            $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE  m.dateMatch BETWEEN :datepost AND DATE_ADD(CURRENT_DATE(), 7, 'day')
                AND ((m.masterProno1 is not null and m.masterProno1 = true) or (m.masterProno2 is not null and m.masterProno2 = true) or (m.masterPronoN is not null and m.masterPronoN = true))
                AND ch.nomChampionat LIKE :championat
                ORDER BY m.dateMatch ASC";
        }

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('championat', $championat);
        if($date){
            $query->setParameter('datepost', $date);
        }
     //   var_dump($query->getDQL()); die;
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
               GROUP BY ch.nomChampionat ";
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


    /**
     * By pays
     * @return array
     */
    public function getListePaysWithChampionatWithMatch()
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE ch.pays is not null";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    function getListeChampionatWithMatchByPays($pays)
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE ch.pays LIKE :pays";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('pays', $pays);
        return $query->getResult();
    }

    function getListeMatchByChampionat($championat)
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                LEFT JOIN ch.teamsPays tp
                WHERE ch.nomChampionat LIKE :championat";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('championat', $championat);
        return $query->getResult();
    }

    function getMatchLiveScore()
    {
        $dql = "SELECT m from ApiDBBundle:Matchs m
                WHERE m.statusMatch LIKE :status ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('status', 'active');
        return $query->getResult();
    }
}