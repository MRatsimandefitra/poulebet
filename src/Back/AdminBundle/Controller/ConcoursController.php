<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Admin;
use Api\DBBundle\Entity\DroitAdmin;
use Api\DBBundle\Entity\Concours;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * Description of ConcoursController
 *
 * @author miora.manitra
 */
class ConcoursController extends ApiController {
    const ENTITY_CONCOURS = 'Api\DBBundle\Entity\Concours';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_TEAMS_PAYS = 'ApiDBBundle:TeamsPays';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    
    const FORM_CONCOURS = 'Api\DBBundle\Form\ConcoursType';
    
    public function indexAction(Request $request){

        $session = new Session();
        
        $session->set("current_page","Concours");
        
        $concours = $this->getAllEntity(self::ENTITY_CONCOURS);

        if($request->get('column')){
            $column = $request->get('column');
        }else{
            $column = null;
        }
        if($request->get('order')){
            $order = $request->get('order');
        }else{
            $order = null;
        }
        $concours = $this->getRepo(self::ENTITY_CONCOURS)->getAllConcours($column, $order);


        $droitAdmin = $this->getDroitAdmin('Lots concours');
        $drt = null;
        if ($droitAdmin){
            $drt = $droitAdmin[0];
        }
        return $this->render('BackAdminBundle:Concours:list.html.twig', array(
                'concours' => $concours,
                'droitAdmin' => $drt
        ));
    }
    private function getDroitAdmin($droit){
        $droitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser(), 'droit' => $this->getRepo(self::ENTITY_DROIT)->findOneBy(array('fonctionnalite' => $droit))));
        return $droitAdmin;
    }
    public function addConcoursAction(Request $request){
        $concours = new Concours();
        $form = $this->formPost(self::FORM_CONCOURS, $concours);
        $form->handleRequest($request);
        $dateDebut = new DateTime('now');
        $dateDebut = $dateDebut->modify('next monday');
        if($form->isValid()){
            $concours->setDateDebut(new \DateTime(date('Y-m-d H:i:s', strtotime($form['dateDebut']->getData()))));
            $concours->setDateFinale(new \DateTime(date('Y-m-d H:i:s', strtotime($form['dateFinale']->getData()))));
            $this->insert($concours, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute("list_concours");
        }
        return $this->render('BackAdminBundle:Concours:add_concours.html.twig', array(
                'form' => $form->createView(),
        ));
    }
    public function editConcoursAction(Request $request){
        $id = $request->get("id");
        $concours = $this->getRepo(self::ENTITY_CONCOURS)->find($id);
        $form = $this->formPost(self::FORM_CONCOURS, $concours);
        $form->handleRequest($request);
        if($form->isValid()){
            $concours->setDateDebut(new \DateTime(date('Y-m-d H:i:s', strtotime($form['dateDebut']->getData()))));
            $concours->setDateFinale(new \DateTime(date('Y-m-d H:i:s', strtotime($form['dateFinale']->getData()))));
            $this->insert($concours);
            return $this->redirectToRoute("list_concours");
        }
        return $this->render('BackAdminBundle:Concours:edit_concours.html.twig', array(
                'form' => $form->createView(),
        ));
    }
    public function removeConcoursAction(Request $request){
        $id = $request->get("id");
        $concours = $this->getRepo(self::ENTITY_CONCOURS)->find($id);
        $this->remove($concours);
        return $this->redirectToRoute("list_concours");
    }
    
    public function addMatchInConcoursAction(Request $request,$id){
        if($request->request->get('identifiant')){
            $identifiant = $request->request->get('identifiant');
            $cote1 = 'cote1_'.$identifiant;
            $coten = 'coten_'.$identifiant;
            $cote2 = 'cote2_'.$identifiant;

            $rCote1 = $request->request->get($cote1);

            $rCoten = $request->request->get($coten);

            $rCote2 = $request->request->get($cote2);

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
            $match->setCot1Pronostic($rCote1);
            $match->setCoteNPronistic($rCoten);
            $match->setCote2Pronostic($rCote2);

            $match->setMasterProno1($rHost);
            $match->setMasterPronoN($rNeutre);
            $match->setMasterProno2($rGuest);

            $this->get('doctrine.orm.entity_manager')->persist($match);
            $this->get('doctrine.orm.entity_manager')->flush();
        }
        $concours = $this->getRepoFormId(self::ENTITY_CONCOURS, $id);
        $championat = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $sm = $this->getServiceMatch();
        $pays = $sm->getCountry();

        $dql ="SELECT m, conc, c from ApiDBBundle:Matchs m
              LEFT JOIN m.concours conc
              LEFT JOIN m.championat c
              "
              ;
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
        $result = $query->getResult();

        $where = array();
        $where[] = " c.isEnable = true";
        $params = array();
        $searchValue = array();

       /* if($request->get('dateDebut')){
            $dateDebut = $request->get('dateDebut');
        }else{
            $dateDebut = date('Y-m-d');
        }
        $searchValue['dateDebut'] = $dateDebut;

        if($request->get('dateFinale')){
            $dateFinale = $request->get('dateFinale');
        }else{
            $now = new \DateTime('now');
            $now = $now->modify('next monday');
            $dateFinale = $now->format('Y-m-d');
        }
        $searchValue['dateFinale'] = $dateFinale;*/

        $dateDebut = $concours->getDateDebut();
        $dateDebut = $dateDebut->format('Y-m-d');

        $dateFinale = $concours->getDateFinale();
        $dateFinale = $dateFinale->format('Y-m-d');
        if($dateDebut && $dateFinale){

            $dateDebut = $dateDebut;
            $where[] = "m.dateMatch BETWEEN :dateStart AND :dateEnd";
            $dateStart = $dateDebut.' 00:00:00';

            $dateFinale = $dateFinale;
            $dateEnd = $dateFinale. ' 23:59:59';

            $params["dateStart"] = $dateStart;
            $params["dateEnd"] = $dateEnd;
            $searchValue['dateDebut'] = $dateDebut;
            $searchValue['dateFinale'] = $dateFinale;

           /* $tmpDateDebut = new \DateTime('now');
            $tmpDateDebut->modify('next monday');
            $tmpDateDebut = $tmpDateDebut->format('Y-m-d h:i:s');
            if($tmpDateDebut == $dateStart){
                $searchValue['date_match_debut'] = $dateStart;
            }


            // date finale
            $tmpDateFinale = new \DateTime($dateDebut);
            $tmpDateFinale->modify('next sunday');
            $tmpDateFinale = $dateFinale->format('Y-m-d');
            if($tmpDateFinale == $dateEnd){
                $searchValue['date_match_finale'] = $dateEnd;
            }*/


        }


        /*if($request->get('date_match_debut') && !$request->get('date_match_finale')){
            $dateMatch = $request->get('date_match_debut');
            $where[] = "m.dateMatch BETWEEN :dateStart AND :dateEnd";
            $dateStart = $dateMatch.' 00:00:00';

            $dateEnd = new \DateTime($dateStart);
            $dateEnd->modify('next sunday');
            $dateEnd = $dateEnd->format('Y-m-d');
            $dateEnd = $dateEnd. ' 23:59:59';

            $params["dateStart"] = $dateStart;
            $params["dateEnd"] = $dateEnd;
            $searchValue['date_match_debut'] = $dateMatch;
        }
        if($request->get('date_match_finale') ){
            $dateMatch = $request->get('date_match_finale');
            $where[] = "m.dateMatch BETWEEN :dateStart AND :dateEnd";
            $dateStart = $dateMatch.' 00:00:00';
            $dateEnd = $dateMatch. ' 23:59:59';

            $params["dateStart"] = $dateStart;
            $params["dateEnd"] = $dateEnd;
            $searchValue['date_match_debut'] = $dateMatch;
        }
        */
        # champinat seul
        if($request->get('championat_match')){

            $cp = $request->get('championat_match');
            $where[] = " c.fullNameChampionat LIKE :championat ";
            $params["championat"] = '%'.$cp.'%';
            $searchValue['championat_match'] = $cp;
        }

        if($request->get('pays_match')){
            $p= $request->get('pays_match');
            $where[] = " c.pays LIKE :pays ";
            $params['pays'] = "%".$p."%";
            $searchValue['pays_match'] = $p;
        }

        if($request->get('status_match')){
            $where[] = " m.statusMatch like :statusMatch";
            $params['statusMatch']= $request->get('status_match');
            $searchValue['status_match'] = $request->get('status_match');
        }
        //var_dump($request->get('withSelection')); die;
        $withSelection = false;
        if($request->get('withSelection')){
           $withSelection = true;
            //$dql .= " LEFT JOIN c";
        }
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        $dql .= " ORDER BY m.dateMatch asc, c.rang asc, m.id asc";
        if(empty($params)){
            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->getResult();
        }else{

            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        }
        // var_dump($matchs); die;
        //var_dump($matchs); die;
        if($request->get('idMatch')){
            $idMatch = $request->get('idMatch');
            $match = $this->getRepoFormId(self::ENTITY_MATCH, $idMatch);
        }
       /* if($request->get('idLotoFoot')){
            $id = $request->get('idLotoFoot');
        }*/
        $data = explode('&', $request->getContent());
        $arrayData = array();
        //$i = 0;
        $idarray = array();
        foreach($data as $vData){
           /* $i = $i + 3;
            if($i > 9){
                var_dump()); die;
            }*/
            if(substr($vData, 0, 7) == 'select_'){
                $arrayData[] = $vData;
                $idarray[] = str_replace('=on','', str_replace('select_','',$vData ));
            }
        }

        foreach($idarray as $vId){
            $matchsEntity = $this->getRepoFormId(self::ENTITY_MATCH, $vId);
            $matchsEntity->addConcour($concours);
            $concours->addMatch($matchsEntity);
            $this->get('doctrine.orm.entity_manager')->persist($matchsEntity);
            $this->get('doctrine.orm.entity_manager')->flush();
        }
        $this->get('doctrine.orm.entity_manager')->persist($concours);
        $this->get('doctrine.orm.entity_manager')->flush();

        $droitAdmin = $this->get('roles.manager')->getDroitAdmin('Lots concours');
        return $this->render('BackAdminBundle:Concours:add_match_concours.html.twig', array(
            'entity' => $concours,
            'championat' => $championat,
            'pays' => $pays,
            'matchs' => $matchs,
            'searchValue' => $searchValue,
            'matchActive' => $this->getServiceMatch()->getTotalItemsMatchsByStatus('active'),
            'matchFinished' => $this->getServiceMatch()->getTotalItemsMatchsByStatus('finished'),
            'matchNonStarted' => $this->getServiceMatch()->getTotalItemsMatchsByStatus('not_started'),
            'totalMatchs' => $this->getServiceMatch()->getTotalMatch(),
            'droitAdmin' => $droitAdmin[0],
            'withSelection' => $withSelection
        ));
    }

    public function removeMatchInConcoursAction(Request $request, $idConcours, $id){
        $match = $this->getRepoFormId(self::ENTITY_MATCH, $id);
        $concours = $this->getRepoFormId(self::ENTITY_CONCOURS, $idConcours);
        $concours->removeMatch($match);
        $match->removeConcour($concours);
        $this->getEm()->persist($match);
        $this->getEm()->persist($concours);
        $this->getEm()->flush();
        //echo(count($lotoFoot->getMatchs()));die();
        return $this->redirectToRoute("add_match_concours", array(
            "id"=>$concours->getId()
        ));
    }
    private function getServiceMatch(){
        $sm = $this->get('matchs.manager');
        return $sm;
    }
}
