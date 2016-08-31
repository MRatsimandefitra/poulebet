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
        $concours = $this->getAllEntity(self::ENTITY_CONCOURS);
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
        if($form->isValid()){
            $this->insert($concours);
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


        $concours = $this->getRepoFormId(self::ENTITY_CONCOURS, $id);
        $championat = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $pays = $this->getAllEntity(self::ENTITY_TEAMS_PAYS);

        $dql ="SELECT m, conc from ApiDBBundle:Matchs m
              LEFT JOIN m.concours conc 
              LEFT JOIN m.championat c 
              "
              ;
        $where = array();
        $params = array();
        $searchValue = array();

        if($request->get('date_match')){
            $dateMatch = $request->get('date_match');
            $where[] = "m.dateMatch BETWEEN :dateStart AND :dateEnd";
            $dateStart = $dateMatch.' 00:00:00';
            $dateEnd = $dateMatch. ' 23:59:59';

            $params["dateStart"] = $dateStart;
            $params["dateEnd"] = $dateEnd;
            $searchValue['date_match'] = $dateMatch;
        }

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
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }

        if(empty($params)){
            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->getResult();
        }else{

            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        }
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

        return $this->render('BackAdminBundle:Concours:add_match_concours.html.twig', array(
            'entity' => $concours,
            'championat' => $championat,
            'pays' => $pays,
            'matchs' => $matchs,
            'searchValue' => $searchValue,
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
}
