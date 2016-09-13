<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class PronosticController extends ApiController
{
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';

    public function indexAction(Request $request)
    {
        //$matchs = $this->getAllEntity(self::ENTITY_MATCH);

        $championat = $this->getAllEntity(self::ENTITY_CHAMPIONAT);

        $where = array();
        $params = array();

        $dql ="SELECT m from ApiDBBundle:Matchs m ";

        if($request->get('date_master_prono')){
            $where[] = " m.dateMatch BETWEEN :dateStart and :dateEnd ";
            $params['dateStart'] = $request->get('date_master_prono'). ' 00:00:00';
            $params['dateEnd'] = $request->get('date_master_prono'). ' 23:59:59';

            $searchValue['date_master_prono'] = $request->get('date_master_prono');
        }

        if($request->get('pays_master_prono') && !$request->get('championat_master_prono')){
            $dql .= " LEFT JOIN m.championat ch ";
            $where[] = "  ch.pays LIKE :pays ";
            $params['pays'] = $request->get('pays_master_prono');
            $searchValue['pays_master_prono'] = $request->get('pays_master_prono');
        }
        if($request->get('championat_master_prono') && !$request->get('pays_master_prono')){
            $dql .= " LEFT JOIN m.championat ch";
            $where[] = ' ch.fullNameChampionat = :championat';
            $params['championat'] = $request->get('championat_master_prono');
            $searchValue['championat_master_prono'] = $request->get('championat_master_prono');
        }
        if($request->get('pays_master_prono') && $request->get('championat_master_prono')){

            $dql .= " LEFT JOIN m.championat ch ";
            $where[] = "  ch.pays LIKE :pays AND ch.fullNameChampionat LIKE :championat ";
            $params['pays'] = $request->get('pays_master_prono');
            $params['championat'] = $request->get('championat_master_prono');
            $searchValue['pays_master_prono'] = $request->get('pays_master_prono');
            $searchValue['championat_master_prono'] = $request->get('championat_master_prono');

        }
        $where[] = " (m.masterProno1 IS NOT NULL OR m.masterPronoN IS NOT NULL OR m.masterProno2 IS NOT NULL)";
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        $droitAdmin = $this->getDroitAdmin('Master Pronostic');
        $dqlCountry = "SELECT ch from ApiDBBundle:Championat ch
                       WHERE ch.pays is not null";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dqlCountry);
        $coutry = $query->getResult();
        return $this->render('BackAdminBundle:Pronostic:index.html.twig', array(
            'matchs' => $matchs,
            'country' => $coutry,
            'championat' => $championat,
            'droitAdmin' => $droitAdmin[0],
            'searchValue' => $searchValue
            /*'items' => $items,
            'totalMatch' => $totalMatch,
            'search' => $dateMatchSearch*/
        ));
    }

    public function removePronosticAction($id){
        $entity = $this->getRepoFormId(self::ENTITY_MATCH, $id);
        try{

            $entity->setMasterProno1(null);

            $entity->setMasterPronoN(null);
            $entity->setMasterProno2(null);
            $entity->setCot1Pronostic(null);
            $entity->setCoteNPronistic(null);
            $entity->setCote2Pronostic(null);
            $this->get('doctrine.orm.entity_manager')->flush();

            return $this->redirectToRoute('index_admin_pronostic');

        }catch(Exception $e){
            return false;
        }


    }


    public function indexPronosticAction(Request $request){
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

        $dql ="SELECT m from ApiDBBundle:Matchs m ";
        $where = array();
        $params = array();
        $searchValue = array();
        if($request->get('dateDebut') && !$request->get('dateFinale')){
            //Todo:: datedebit
            $dateDebut  = $request->get('dateDebut').' 00:00:00';
            $dateFinaleSearch = $request->get('dateDebut'). ' 23:59:59';

            $where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinaleSearch ";
            $params['dateDebut'] = $dateDebut;
            $params['dateFinaleSearch'] = $dateFinaleSearch;
            $searchValue['dateDebut'] = $request->get('dateDebut');

        }

        if($request->get('dateDebut') && $request->get('dateFinale')){
            $dateDebut  = $request->get('dateDebut').' 00:00:00';
            $dateFinaleSearch = $request->get('dateFinale'). ' 23:59:59';

            $where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinale ";
            $params['dateDebut'] = $dateDebut;
            $params['dateFinale'] = $dateFinaleSearch;
            $searchValue['dateDebut'] = $request->get('dateDebut');
            $searchValue['dateFinale'] = $request->get('dateFinale');
        }
        $requestChampionat = false;
        # champinat seul
        if($request->get('championat_match')&& !$request->get('pays_match')){
            $championat = $request->get('championat_match');
            $dql .= " LEFT JOIN m.championat c";
            $where[] = " c.fullNameChampionat LIKE :championat ";
            $params["championat"] = '%'.$championat.'%';
            $searchValue['championat_match'] = $championat;
        }

        if($request->get('pays_match') && !$request->get('championat_match')){
            $pays= $request->get('pays_match');
            $dql .= " LEFT JOIN m.championat c";
            $where[] = " c.pays LIKE :pays ";
            $params['pays'] = "%".$pays."%";
            $searchValue['pays_match'] = $pays;
        }

        if($request->get('championat_match') && $request->get('pays_match')){
            $championat = $request->get('championat_match');
            $dql .= " LEFT JOIN m.championat c ";

            $pays= $request->get('pays_match');
            /*$dql .= " LEFT JOIN c.teamsPays tp";*/

            $where[] = " c.fullNameChampionat LIKE :championat ";
            $where[] = " c.pays LIKE :pays";

            $params['pays'] = "%".$pays."%";
            $params["championat"] = '%'.$championat.'%';

            $searchValue['championat_match'] = $championat;
            $searchValue['pays_match'] = $pays;

        }
        if(!$request->get('championat_match') && !$request->get('pays_match') && !$request->get('dateDebut') && !$request->get('dateFinale')){
            $where[] = " m.dateMatch BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), 7,'day')";
        }
        $orderByChampionat = false;
        if ($request->query->get('column') && $request->query->get('tri')) {

            //var_dump("ORDER BY ".$request->query->get('column'). " ".$request->query->get('tri')); die;$*
            //   $dql .= " ORDER BY ".$request->query->get('column'). " ". strtoupper($request->query->get('tri'));
            if ($requestChampionat) {
                $dql .= " ORDER BY " . $request->query->get('column') . " " . strtoupper($request->query->get('tri'));
            } else {
                if ($request->query->get('column') == 'ch.fullNameChampionat') {
                    $dql .= " LEFT JOIN m.championat ch ";
                    $orderByChampionat = true;

                } else {
                    $orderByChampionat = true;
                    //$dql .= " ORDER BY ".$request->query->get('column'). " ". strtoupper($request->query->get('tri'));
                }

            }

        }
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        if ($orderByChampionat) {
            $dql .= " ORDER BY " . $request->query->get('column') . " " . strtoupper($request->query->get('tri'));
        } else {
            $dql .= ' ORDER BY m.dateMatch asc';
        }
        var_dump($dql); die;
        if(empty($params)){

            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->getResult();
        }else{
            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        }
        $totalRecherche = count($matchs);
        $championatData = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $dqli = "SELECT ch From ApiDBBundle:championat ch where ch.pays  is not null ";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dqli);
        $country = $query->getResult();
        $droitAdmin = $this->getDroitAdmin('Matchs');
        return $this->render('BackAdminBundle:Pronostic:index_pronostic.html.twig', array(
            'matchs' => $matchs,
            'championat' => $championatData,
            'country' => $country,
            'searchValue' => $searchValue,
            'droitAdmin' => $droitAdmin[0],
            'totalRecherche' => $totalRecherche,
            'totalItemsActif' => $this->getTotalItemsMatchsByStatus('active'),
            'totalItemsFinished' => $this->getTotalItemsMatchsByStatus('finished'),
            'totalItemsMatchs' => $this->getTotalItemsMatchsByStatus(),
            'totalItemsNotStarted' => $this->getTotalItemsMatchsByStatus('not_started'),
            'totalPronostic' => $this->getTotalPronostic()

        ));
    }
    private function getTotalPronostic(){
        $totalPronostic = $this->get('matchs.manager')->getTotalPronostic();
        return $totalPronostic;
    }
    private function getTotalItemsMatchsByStatus($status = null){
        $matchsManager = $this->get('matchs.manager')->getTotalItemsMatchsByStatus($status);
        return $matchsManager;
    }




    private function getDroitAdmin($droit){
        $droitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser(), 'droit' => $this->getRepo(self::ENTITY_DROIT)->findOneBy(array('fonctionnalite' => $droit))));
        return $droitAdmin;
    }
}
