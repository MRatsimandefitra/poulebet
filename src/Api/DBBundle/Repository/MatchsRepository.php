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
                WHERE ch.isEnable = true ";
        /*CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat*/
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
                WHERE ch.isEnable = true
                GROUP BY ch.nomChampionat ORDER BY ch.rang ASC";
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
        $params = array();
        if(!$date){
            $dql = "SELECT m from ApiDBBundle:Matchs m
                    LEFT JOIN m.championat ch
                    WHERE m.dateMatch BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), 7, 'day')
                    AND ((m.masterProno1 is not null and m.masterProno1 = true) or (m.masterProno2 is not null and m.masterProno2 = true) or (m.masterPronoN is not null and m.masterPronoN = true))
                    AND   ch.nomChampionat LIKE :championat
                    ORDER BY ch.rang ASC, m.dateMatch ASC, m.id ASC";
            //AND (m.masterProno1 is not null or m.masterProno2 is not null or m.masterPronoN is not null)

        }else{

            $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                WHERE  m.dateMatch BETWEEN :date1 and :date2
                AND ((m.masterProno1 is not null and m.masterProno1 = true) or (m.masterProno2 is not null and m.masterProno2 = true) or (m.masterPronoN is not null and m.masterPronoN = true))
                AND ch.nomChampionat LIKE :championat
                ORDER BY ch.rang ASC, m.dateMatch ASC, m.id ASC";
            $params['date1'] = $date. ' 00:00:00';
            $params['date2'] = $date. ' 23:59:59';
        }
        $params['championat'] = $championat;
        if(!empty($params)){
           $query = $this->getEntityManager()->createQuery($dql)->setParameters($params);
       }else{
           $query = $this->getEntityManager()->createQuery($dql);
       }
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
               GROUP BY ch.nomChampionat ORDER BY ch.rang ASC  ";
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
                WHERE isEnable = true
                AND tp.name LIKE :pays
                OR tp.fullName LIKE :pays ORDER BY ch.rang ASC";
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
                WHERE ch.pays is not null ORDER BY ch.rang ASC";
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

    function findMatchPronosticByParameter($championnat = null, $date = null, $groupChampionat = null){

        $dql  = "SELECT m, ch from ApiDBBundle:Matchs m  LEFT JOIN m.championat ch ";
        $where = array();
        if($championnat){
            $where[] = " ch.nomChampionat = :championat ";
            $params['championat'] = $championnat;
        }
        if($date && $date != ''){
            $where[]  = " m.dateMatch BETWEEN :date1 and :date2";
            $params['date1'] = $date.' 00:00:01 ';
            $params['date2'] = $date.' 23:59:59 ';
        }else{

            $where[] = "   m.dateMatch BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), 7, 'day')";
        }
        $where[] = " ((m.masterProno1 is not null and m.masterProno1 = TRUE) or (m.masterPronoN is not null and m.masterPronoN = TRUE) or (m.masterProno2 is not null and m.masterProno2 = TRUE)) ";
        $where[] = " ch.isEnable = true";
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        if($groupChampionat){
            $dql .= " GROUP BY ch.nomChampionat ";
        }
        $dql .= " ORDER BY ch.rang ASC, m.dateMatch ASC, m.id ASC ";
        $query = $this->getEntityManager()->createQuery($dql);
        if(!empty($params)){
            $query = $this->getEntityManager()->createQuery($dql)->setParameters($params);
        }

        return $query->getResult();
    }

    public function findMatchsForPari($date = null, $championat = null, $groupChampionat = null, $idConcour =null){

        $dql = "Select m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                JOIN m.concours co ";


        $params = array();
        $where = array();

        $where[] = " m.dateMatch BETWEEN co.dateDebut AND co.dateFinale";
        $where[] = " co.id = :idConcour";
        $params['idConcour'] = $idConcour;
        if($date){

            $where[] = " m.dateMatch BETWEEN :date1 AND :date2 ";
            $params['date1'] = $date. " 00:00:00";
            $params['date2'] = $date. " 23:59:59";
        }
        if($championat){
            $where[] = " ch.nomChampionat LIKE :championat";
            $params['championat'] = $championat;
        }

        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        if($groupChampionat){
            $dql .= " GROUP BY ch.nomChampionat";
        }
        $dql .= " ORDER BY m.dateMatch ASC, ch.rang ASC";
        if(empty($params)){
            $query = $this->getEntityManager()->createQuery($dql);
        }else{
            $query = $this->getEntityManager()->createQuery($dql)->setParameters($params);
        }

        return $query->getResult();

    }

    public function findMatchsForPariNoJouer($date = null, $championat = null, $groupChampionat = null, $idUser,$idMatchs ){

        $dql = "Select m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                JOIN m.concours co ";

        $params = array();
        $where = array();
        /*$where[] = " NOT EXISTS (SELECT vu from ApiDBBundle:VoteUtilisateur vu LEFT JOIN vu.matchs m LEFT JOIN vu.utilisateur u where u.id = :idUser AND m.id = :idMatchs)";
        $params['idUser'] = $idUser;
        $params['idMatchs'] = $idMatchs;*/
        if($date){
            $where[] = " m.dateMatch BETWEEN :date1 AND :date2 ";
            $params['date1'] = $date. " 00:00:00";
            $params['date2'] = $date. " 23:59:59";
        }
        if($championat){
            $where[] = " ch.nomChampionat LIKE :championat";
            $params['championat'] = $championat;
        }

        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        if($groupChampionat){
            $dql .= " GROUP BY ch.nomChampionat";
        }
        $dql .= " ORDER BY m.dateMatch ASC, ch.rang ASC";

        // var_dump($dql); die;
        if(empty($params)){
            $query = $this->getEntityManager()->createQuery($dql);
        }else{

            $query = $this->getEntityManager()->createQuery($dql)->setParameters($params);
        }
        return $query->getResult();

    }

    function findMatchVote($userId = null, $date = null, $championat = null ){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN vu.matchs m
                LEFT JOIN m.championat ch
                LEFT JOIN vu.utilisateur u ";
        $where = array();
        $params = array();
        if($userId){
            $where[] = " u.id = :idUser ";
            $params['idUser'] = $userId;
        }

        if($date){
            $where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinale ";

            $date1 = $date. ' 00:00:00';
            $date2 = $date. ' 23:59:59';
            $params['dateDebut'] = $date1;
            $params['dateFinale'] = $date2;
            /*$dql .= " WHERE m.dateMatch BETWEEN '".$date1."' AND  '" .$date2. "' ";*/

        }
        if($championat){
            $where[] = " ch.nomChampionat LIKE :championat ";
            $params['championat'] = $championat;
        }
        if(!empty($where)){
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        $dql .=" ORDER BY m.dateMatch ASC, ch.rang ASC ";
        if(empty($params)){
            $query = $this->getEntityManager()->createQuery($dql);
        }else{
            $query = $this->getEntityManager()->createQuery($dql)->setParameters($params);
        }

        return $query->getResult();
    }
    public function findGains($idUser, $idMatchs, $idVote){

        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN vu.matchs m
                LEFT JOIN vu.utilisateur u
                WHERE u.id = :idUser AND m.id = :idMatchs And vu.id = :idVote";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(array('idUser' => $idUser, 'idMatchs' => $idMatchs,'idVote' => $idVote));
        return $query->getResult();
    }

    public function findDetailsJouer($idMatchs, $userId){

        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN vu.matchs m
                LEFT JOIN vu.utilisateur u WHERE m.id = :idMatch AND u.id = :userId";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(array('idMatch' => $idMatchs, 'userId' => $userId));
        return $query->getResult();
    }

    public function findIdConcourByDate(){
        $dql = "SELECT co from ApiDBBundle:Concours co where CURRENT_DATE()  BETWEEN co.dateDebut AND co.dateFinale";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }


    public function findMatchsForRecapCombined($idUser, $idMise){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu LEFT JOIN vu.matchs m LEFT JOIN vu.utilisateur u LEFT JOIN m.championat ch
                WHERE u.id = :idUser And vu.idMise = :idMise ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(array('idUser' => $idUser, 'idMise' => $idMise));
        return $query->getResult();
    }
    public function findChampionatForRecapCombined($idUser){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu LEFT JOIN vu.matchs m LEFT JOIN vu.utilisateur u LEFT JOIN m.championat ch
                WHERE u.id = :idUser AND vu.isCombined = true GROUP BY ch.nomChampionat ";

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameters(array('idUser' => $idUser));
        return $query->getResult();
    }

    public function findNbMatchsForRecapCombined($idUser){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu LEFT JOIN vu.matchs m LEFT JOIN vu.utilisateur u
                WHERE u.id = :idUser AND vu.isCombined = true GROUP BY vu.idMise ";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('idUser' , $idUser);
        return $query->getResult();
    }

    public function findNbMatchsVoteSimple($userId){
        $dql ="SELECT vu from ApiDBBundle:VoteUtilisateur vu LEFT JOIN vu.matchs m LEFT JOIN vu.utilisateur u
               WHERE u.id = :idUser AND vu.isCombined = false GROUP BY vu.idMise";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('idUser' , $userId);
        return $query->getResult();
    }

    public function findChampionatVoteSimple($userId){
        $dql ="SELECT vu from ApiDBBundle:VoteUtilisateur vu LEFT JOIN vu.matchs m LEFT JOIN vu.utilisateur u LEFT JOIN m.championat ch
               WHERE u.id = :idUser AND vu.isCombined = false GROUP BY ch.nomChampionat";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('idUser' , $userId);
        return $query->getResult();
    }

    public function findMatchsForRecap(){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN vu.matchs m
                LEFT JOIN vu.utilisateur u";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    public function findMatchsExisitInVote($idMatchs){

        $dql ="SELECT vu from ApiDBBundle:VoteUtilisateur vu
               LEFT JOIN vu.matchs m WHERE m.id = :idMatchs AND vu.gagnant IS NULL";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('idMatchs', $idMatchs);
        return $query->getResult();
    }

    public function findRecapForNotification(){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu LEFT JOIN vu.matchs m";

    }

    public function findMatchsForCote($dateMatchs, $equipeDomicile, $equipeVisiteur){
        $date = date('Y-m-d', strtotime($dateMatchs));
        $date1 = $date.' 00:00:00';
        $date2 = $date. ' 23:59:59';

        $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.teamsDomicile td
                LEFT JOIN m.teamsVisiteur tv
                WHERE m.dateMatch BETWEEN :date1 AND :date2
                AND (
                (td.nomClub LIKE :equipeDomicile OR td.fullNameClub LIKE :equipeDomicile)
                    OR
                (tv.nomClub LIKE :equipeVisiteur OR td.fullNameClub LIKE :equipeVisiteur)
                )";

        $query = $this->getEntityManager()->createQuery($dql);
       // $query->setParameter('dateMatchs', $dateMatchs);
        $query->setParameter('date1', $date1);
        $query->setParameter('date2', $date2);
        $query->setParameter('equipeDomicile', '%'.$equipeDomicile.'%');
        $query->setParameter('equipeVisiteur', '%'.$equipeVisiteur.'%');
        //$query->setParameter()
        return $query->getResult();
    }

    public function findMatchVoteGagnant(){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN vu.matchs m
                LEFT JOIN vu.utilisateur u
                WHERE vu.gagnant is null";
        $query = $this->getEntityManager()->createQuery($dql);
        return $query->getResult();
    }

    public  function findStateForCombined($utilisateurId){
        $dql = "SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN vu.matchs m
                LEFT JOIN vu.utilisateur u
                WHERE u.id = :utilisateurId AND vu.isCombined = 1 group by vu.dateMise";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('utilisateurId', $utilisateurId);
        return $query->getResult();
    }

    public function findMatchsPariSimple($idConcour , $date = null, $championat = null){
        $dql = "SELECT m from ApiDBBundle:Matchs m JOIN m.concours co where co.id = :idConcour";
        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('idConcour' , $idConcour);
        return $query->getResult();
    }
}