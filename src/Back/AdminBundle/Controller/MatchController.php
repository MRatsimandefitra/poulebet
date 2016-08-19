<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\LotoFoot15;
use Api\DBBundle\Entity\LotoFoot7;
use Api\DBBundle\Entity\Match;
use Api\DBBundle\Entity\matchIndividuel;
use Api\DBBundle\Entity\Matchs;
use Back\AdminBundle\Resources\Request\MatchSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class MatchController extends ApiController
{
    const FORM_MATCH_SEARCH = 'Api\DBBundle\Form\MatchSearchType';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_LOTOFOOT7 = 'ApiDBBundle:LotoFoot7';
    const ENTITY_LOTOFOOT15 = 'ApiDBBundle:LotoFoot15';

    public function indexAction(Request $request)
    {
        if($request->request->get('identifiant')){
            $identifiant = $request->request->get('identifiant');
            $cote1 = 'cote1_'.$identifiant;
            $coten = 'coten_'.$identifiant;
            $cote2 = 'cote2_'.$identifiant;

            /*if($request->request->get($cote1)){*/
                $rCote1 = $request->request->get($cote1);
            /*}*/

           /* if($request->request->get($coten)){
                die('okok');*/
                $rCoten = $request->request->get($coten);
            /*}*/

            /*if($request->request->get($cote2)){*/
                $rCote2 = $request->request->get($cote2);
            /*}*/

            $cHost = 'c_host_'.$identifiant;
            $rHost = false;
            if($request->request->get($cHost) == 'on'){
                $rHost = true;
            }

            $cNeutre = 'c_neutre_'.$identifiant;
            $rNeutre = false;
            if($request->request->get($cNeutre) == 'on'){
                $rNeutre = true;
            }
            $cGuest = 'c_guest_'.$identifiant;
            $rGuest = false;
            if($request->request->get($cGuest) == "on"){
                $rGuest = true;
            }

            $match = $this->getRepoFormId(self::ENTITY_MATCH, $identifiant);
            /*if($rCote1){*/
                $match->setCot1Pronostic($rCote1);
            /*}*/
            /*if($rCoten){*/
                $match->setCoteNPronistic($rCoten);
            /*}*/
            /*if($rCote2){*/
                $match->setCote2Pronostic($rCote2);
            /*}*/

            $match->setMasterProno1($rHost);
            $match->setMasterPronoN($rNeutre);
            $match->setMasterProno2($rGuest);

            $this->get('doctrine.orm.entity_manager')->persist($match);
            $this->get('doctrine.orm.entity_manager')->flush();
        }

        $dql ="SELECT m from ApiDBBundle:Matchs m ";
        $where = array();
        $params = array();
        /*
        if ($name = $request->getName()) {
            $where[] = "b.name LIKE :name";
            $params["name"] = "%" . $name . "%";
        }

        if (is_numeric($request->getStatus())) {
            $where[] = "b.status = :status";
            $params["status"] = $request->getStatus();
        }
*/

        $searchValue = array();
        #datematch
        if($request->get('date_match') && !$request->get('time_match')){

            $dateMatch = $request->get('date_match');
            $where[] = "m.dateMatch BETWEEN :dateStart AND :dateEnd";
            $dateStart = $dateMatch.' 00:00:00';
            $dateEnd = $dateMatch. ' 23:59:59';

            $params["dateStart"] = $dateStart;
            $params["dateEnd"] = $dateEnd;
            $searchValue['date_match'] = $dateMatch;
        }

        #datematch heure match
        if($request->get('time_match') && $request->get('date_match')){
            $heureMatch = $request->get('time_match');
            $where[] = "m.dateMatch = :heure";
            $heure = $request->get('date_match').' '.$heureMatch;

            $params["heure"] = $heure;
            $searchValue['date_match'] = $request->get('date_match');
            $searchValue['time_match'] = $heureMatch;
        }

        # champinat seul
        if($request->get('championat_match')){

            $championat = $request->get('championat_match');
            $dql .= " LEFT JOIN m.championat c";
            $where[] = " c.nomChampionat LIKE :championat ";
            $params["championat"] = '%'.$championat.'%';
            $searchValue['championat_match'] = $championat;
        }

        if($request->get('pays_match')){
            $pays= $request->get('pays_match');
            $where[] = " m.equipeVisiteur LIKE :pays or m.equipeDomicile LIKE :pays ";
            $params['pays'] = "%".$pays."%";
            $searchValue['pays_match'] = $pays;
        }

        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }

        if(empty($params)){

            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->getResult();
        }else{

            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        }
        //$this->em()->createQuery($dql)->setParameters($params);
        //var_dump($dql); die;
        $championatData = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $country = $this->getAllEntity(self::ENTITY_COUNTRY);
        return $this->render('BackAdminBundle:Matchs:index.html.twig', array(
            'matchs' => $matchs,
            'championat' => $championatData,
            'country' => $country,
            'searchValue' => $searchValue
            /*'items' => $items,
            'totalMatch' => $totalMatch,
            'search' => $dateMatchSearch,
            'dateSearch' => $dateMatchSearch,
            'timeSearch' => $heureMatchSearch,
            'country' => $country,
            'championat' => $championatData*/
            /*'form' => $form->createView()*/
        ));
    }

    public function addLotoFootAction(Request $request){

        if($request->get('numero')){
            $numero = $request->get('numero');
        }
        if($request->get('finvalidation')){
            $finValidation = $request->get('finvalidation');
        }
        if($request->get('lotofoot')){
            $lotofoot = $request->get('lotofoot');
            if($lotofoot =='lf7'){
                $lotofoot7 = new LotoFoot7();
                $lotofoot7->setNumero($numero);
                $date = new \DateTime($finValidation);
                $lotofoot7->setFinValidation($date);
                $this->insert($lotofoot7, array('success' => 'success' , 'error' => 'error'));
            }
            if($lotofoot == 'lf15'){
                $lotofoot15 = new LotoFoot15();
                $lotofoot15->setNumero($numero);

                $lotofoot15->setFinValidation(new \DateTime($finValidation));
                $this->insert($lotofoot15, array('success' => 'success' , 'error' => 'error'));
            }
        }



        return $this->render('BackAdminBundle:Matchs:add_loto_foot.html.twig', array(

        ));
    }

    public function listLotofootAction(Request $request){

        $lotoFoot7 = $this->getAllEntity(self::ENTITY_LOTOFOOT7);
        $lotoFoot15 = $this->getAllEntity(self::ENTITY_LOTOFOOT15);

        return $this->render('BackAdminBundle:Matchs:list_lotofoot.html.twig', array(
                'lotoFoot7' => $lotoFoot7,
                'lotoFoot15' => $lotoFoot15
        ));
    }

    public function editLotofootAction(Request $request, $id, $idLotoFoot){

        if($idLotoFoot == 7){
            $currentLotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT7, $id);
        }
        if($idLotoFoot == 15){
            $currentLotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT15, $id);
        }

        if($request->get('numero')){
            $numero = $request->get('numero');
        }
        if($request->get('finvalidation')){
            $finValidation = $request->get('finvalidation');
        }
        if($request->get('lotofoot')){
            $lotofoot = $request->get('lotofoot');
            if($lotofoot =='lf7'){
                $lotofoot7 = $this->getRepoFormId(self::ENTITY_LOTOFOOT7, $id);
                $lotofoot7->setNumero($numero);
                $date = new \DateTime($finValidation);
                $lotofoot7->setFinValidation($date);
                $this->insert($lotofoot7, array('success' => 'success' , 'error' => 'error'));
            }
            if($lotofoot == 'lf15'){
                $lotofoot15 = $this->getRepoFormId(self::ENTITY_LOTOFOOT15, $id);
                $lotofoot15->setNumero($numero);

                $lotofoot15->setFinValidation(new \DateTime($finValidation));
                $this->insert($lotofoot15, array('success' => 'success' , 'error' => 'error'));
            }
        }
        return $this->render('BackAdminBundle:Matchs:edit_lotofoot.html.twig', array(
                'currentLotoFoot' => $currentLotoFoot,
                'idLotoFoot' => $idLotoFoot
        ));
    }


    public function removeLotoFootAction(){

    }
}
