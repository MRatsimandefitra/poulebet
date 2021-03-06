<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Command\GoalApiCommand;
use Api\CommonBundle\Command\GoalApiMatchsLiveCommand;
use Api\CommonBundle\Command\GoalApiMatchsManuelCommand;
use Api\CommonBundle\Command\GoalApiMatchsParChampionatCommand;
use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\LotoFoot;
use Api\DBBundle\Entity\LotoFoot15;
use Api\DBBundle\Entity\LotoFoot7;
use Api\DBBundle\Entity\Match;
use Api\DBBundle\Entity\matchIndividuel;
use Api\DBBundle\Entity\Matchs;
use Back\AdminBundle\Resources\Request\MatchSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;

class MatchController extends ApiController
{
    const FORM_MATCH_SEARCH = 'Api\DBBundle\Form\MatchSearchType';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:TeamsPays';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_LOTOFOOT = 'ApiDBBundle:LotoFoot';
    const FORM_LOTOFOOT = 'Api\DBBundle\Form\LotoFootType';

    const ENTITY_LOTOFOOT7 = 'ApiDBBundle:LotoFoot7';
    const ENTITY_LOTOFOOT15 = 'ApiDBBundle:LotoFoot15';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    const DROITS = 'Matchs';
    const FORM_MATCHS = 'Api\DBBundle\Form\MatchsType';
    const FORM_CHAMPIONAT = 'Api\DBBundle\Form\ChampionatType';
    const ENTITY_TEAMS_PAYS = 'ApiDBBundle:TeamsPays';

   /* const ENTITY_LOTOFOOT = 'ApiDBBundle:LotoFoot';
    const FORM_LOTOFOOT = 'Api\DBBundle\Form\LotoFootType';*/

    public function indexMatchsAction(Request $request)
    {
        $session = new Session();

        $session->set("current_page","Matchs");
        //var_dump($request->get('dateFinale')); die;
        if ($request->request->get('identifiant')) {
            $identifiant = $request->request->get('identifiant');
            $cote1 = 'cote1_' . $identifiant;
            $coten = 'coten_' . $identifiant;
            $cote2 = 'cote2_' . $identifiant;

            $rCote1 = $request->request->get($cote1);
            $rCoten = $request->request->get($coten);
            $rCote2 = $request->request->get($cote2);
            $cHost = 'c_host_' . $identifiant;
            $rHost = false;
            if ($request->request->get($cHost) == 'on') {
                $rHost = true;
            }

            $cNeutre = 'c_neutre_' . $identifiant;
            $rNeutre = false;
            if ($request->request->get($cNeutre) == 'on') {
                $rNeutre = true;
            }
            $cGuest = 'c_guest_' . $identifiant;
            $rGuest = false;
            if ($request->request->get($cGuest) == "on") {
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

        $dql = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat c
               LEFT JOIN c.teamsPays tp";
        $where = array();
        $where[] = " c.isEnable = true ";
        $params = array();
        $searchValue = array();
        if ($request->get('dateDebut') && !$request->get('dateFinale')) {
            //Todo:: datedebit
            $dateDebut = $request->get('dateDebut') . ' 00:00:00';
            $dateFinaleSearch = $request->get('dateDebut') . ' 23:59:59';

            $where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinaleSearch ";
            $params['dateDebut'] = $dateDebut;
            $params['dateFinaleSearch'] = $dateFinaleSearch;
            $searchValue['dateDebut'] = $request->get('dateDebut');

        }
        $dd = new \DateTime('now');

        $searchValue['date_debut'] = $dd->format('Y-m-d');;

        $df = new \DateTime('now');
        $dff = $df->modify('+7 day');

        $searchValue['date_finale'] = $dff->format('Y-m-d');;

        if ($request->get('dateDebut')) {
            $dateDebutRequest = $request->get('dateDebut');
        } else {
            $dateDebutRequest = $searchValue['date_debut'];
        }
        if ($request->get('dateFinale')) {
            $dateFinaleRequest = $request->get('dateFinale');
        } else {
            $dateFinaleRequest = $searchValue['date_finale'];
        }
        $filter = false;
        if ($dateDebutRequest && $dateFinaleRequest) {
            $dateDebut = $dateDebutRequest . ' 00:00:00';
            $dateFinaleSearch = $dateFinaleRequest . ' 23:59:59';

            /*$where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinale ";
            $params['dateDebut'] = $dateDebut;
            $params['dateFinale'] = $dateFinaleSearch;*/
            $searchValue['dateDebut'] = date('Y-m-d', strtotime($dateDebut));
            $searchValue['dateFinale'] = date('Y-m-d', strtotime($dateFinaleSearch));
            $filter = true;
        }
        # champinat seul
        $requestChampionat = false;

        if ($dateDebutRequest && $dateFinaleRequest) {

            $dateDebut = $dateDebutRequest . ' 00:00:00';
            $dateFinaleSearch = $dateFinaleRequest . ' 23:59:59';

            $where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinale ";
            $params['dateDebut'] = $dateDebut;
            $params['dateFinale'] = $dateFinaleSearch;
            $searchValue['dateDebut'] = date('Y-m-d', strtotime($dateDebut));
            $searchValue['dateFinale'] = date('Y-m-d', strtotime($dateFinaleSearch));
            $filter = true;
        }
        if ($request->get('championat_match') && !$request->get('pays_match')) {

            $championat = $request->get('championat_match');
            //$dql .= " LEFT JOIN m.championat c";
            $where[] = " c.fullNameChampionat LIKE :championat ";
            $params["championat"] = '%' . $championat . '%';
            $searchValue['championat_match'] = $championat;
            $requestChampionat = true;
        }

        if ($request->get('pays_match') && !$request->get('championat_match')) {
            $pays = $request->get('pays_match');
            //$dql .= " LEFT JOIN m.championat c";
            $where[] = " c.pays LIKE :pays ";
            $params['pays'] = "%" . $pays . "%";
            $searchValue['pays_match'] = $pays;
        }

        if ($request->get('championat_match') && $request->get('pays_match')) {
            $championat = $request->get('championat_match');
            //$dql .= " LEFT JOIN m.championat c ";

            $pays = $request->get('pays_match');
            /*$dql .= " LEFT JOIN c.teamsPays tp";*/

            $where[] = " c.fullNameChampionat LIKE :championat ";
            $where[] = " c.pays LIKE :pays";

            $params['pays'] = "%" . $pays . "%";
            $params["championat"] = '%' . $championat . '%';

            $searchValue['championat_match'] = $championat;
            $searchValue['pays_match'] = $pays;

        }
        if ($request->get('match_status')) {
            $where[] = " m.statusMatch LIKE :match_status ";
            $match_status = $request->get('match_status');
            $params['match_status'] = "%" . $match_status . "%";
            $searchValue['match_status'] = $match_status;
        }
        /*        }else{
                    if(!$filter){
                        $searchValue['dateDebut'] = "";
                        $searchValue['dateFinale'] = "";
                    }

                    $searchValue['match_status'] = "";
                    $searchValue['pays_match'] = "";
                    $searchValue['championat_match'] = "";
                    $searchValue['pays_match'] = "";
                }*/


        $orderByChampionat = false;
        if ($request->query->get('column') && $request->query->get('tri')) {

            //var_dump("ORDER BY ".$request->query->get('column'). " ".$request->query->get('tri')); die;$*
            //   $dql .= " ORDER BY ".$request->query->get('column'). " ". strtoupper($request->query->get('tri'));
            if ($requestChampionat) {
                $dql .= " ORDER BY " . $request->query->get('column') . " " . strtoupper($request->query->get('tri'));
            } else {
                if ($request->query->get('column') == 'ch.fullNameChampionat') {
                    //$dql .= " LEFT JOIN m.championat ch ";
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

        if ($request->get('filter') == 'nofilter') {

            $dql = "SELECT m from ApiDBBundle:Matchs m LEFT JOIN m.championat ch
               LEFT JOIN c.teamsPays tp WHERE c.isEnable =true";
            $searchValue['dateDebut'] = "";
            $searchValue['dateFinale'] = "";

            $searchValue['match_status'] = "";
            $searchValue['pays_match'] = "";
            $searchValue['championat_match'] = "";
            $searchValue['pays_match'] = "";
            $params = array();
        }

        if ($orderByChampionat) {
            $dql .= " ORDER BY " . $request->query->get('column') . " " . strtoupper($request->query->get('tri'));
        } else {
            $dql .= ' ORDER BY m.dateMatch asc, c.rang asc, m.id asc';
        }
        if (empty($params)) {

            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->getResult();

        } else {

            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        }

        $championatData = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $dqli = "SELECT ch From ApiDBBundle:championat ch where ch.pays  is not null ";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dqli);
        $country = $query->getResult();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $matchs, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $droitAdmin = $this->getDroitAdmin('Matchs');


        // get Total Match aujourdhui

        return $this->render('BackAdminBundle:Matchs:indexMatchs.html.twig', array(
            'matchs' => $matchs,
            'championat' => $championatData,
            'country' => $country,
            'searchValue' => $searchValue,
            'droitAdmin' => $droitAdmin[0],
            'pagination' => $pagination,
            'totalItemsActif' => $this->getTotalItemsMatchsByStatus('active'),
            'totalItemsFinished' => $this->getTotalItemsMatchsByStatus('finished'),
            'totalItemsMatchs' => $this->getTotalItemsMatchsByStatus(),
            'totalItemsNotStarted' => $this->getTotalItemsMatchsByStatus('not_started'),

        ));


    }

    private function getTotalItemsMatchsByStatus($status = null)
    {
        $matchManager = $this->get('matchs.manager')->getTotalItemsMatchsByStatus($status);
        return $matchManager;
    }

    public function indexAction(Request $request)
    {
        if ($request->request->get('identifiant')) {
            $identifiant = $request->request->get('identifiant');
            $cote1 = 'cote1_' . $identifiant;
            $coten = 'coten_' . $identifiant;
            $cote2 = 'cote2_' . $identifiant;

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

            $cHost = 'c_host_' . $identifiant;
            $rHost = false;
            if ($request->request->get($cHost) == 'on') {
                $rHost = true;
            }

            $cNeutre = 'c_neutre_' . $identifiant;
            $rNeutre = false;
            if ($request->request->get($cNeutre) == 'on') {
                $rNeutre = true;
            }
            $cGuest = 'c_guest_' . $identifiant;
            $rGuest = false;
            if ($request->request->get($cGuest) == "on") {
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

        $dql = "SELECT m from ApiDBBundle:Matchs m ";
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
        /*if($request->get('date_match') && !$request->get('time_match')){

            $dateMatch = $request->get('date_match');
            $where[] = "m.dateMatch BETWEEN :dateStart AND :dateEnd";
            $dateStart = $dateMatch.' 00:00:00';
            $dateEnd = $dateMatch. ' 23:59:59';

            $params["dateStart"] = $dateStart;
            $params["dateEnd"] = $dateEnd;
            $searchValue['date_match'] = $dateMatch;
        }*/

        #datematch heure match
        /* if($request->get('time_match') && $request->get('date_match')){
             $heureMatch = $request->get('time_match');
             $where[] = "m.dateMatch = :heure";
             $heure = $request->get('date_match').' '.$heureMatch;

             $params["heure"] = $heure;
             $searchValue['date_match'] = $request->get('date_match');
             $searchValue['time_match'] = $heureMatch;
         }*/

        if ($request->get('dateDebut') && !$request->get('dateFinale')) {
            //Todo:: datedebit
            $dateDebut = $request->get('dateDebut') . ' 00:00:00';
            $dateFinaleSearch = $request->get('dateDebut') . ' 23:59:59';

            $where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinaleSearch ";
            $params['dateDebut'] = $dateDebut;
            $params['dateFinale'] = $dateFinaleSearch;
            $searchValue['dateDebut'] = $request->get('dateDebut');

        }

        if ($request->get('dateDebut') && $request->get('dateFinale')) {
            $dateDebut = $request->get('dateDebut') . ' 00:00:00';
            $dateFinaleSearch = $request->get('dateFinale') . ' 23:59:59';

            $where[] = " m.dateMatch BETWEEN :dateDebut AND :dateFinale ";
            $params['dateDebut'] = $dateDebut;
            $params['dateFinale'] = $dateFinaleSearch;
            $searchValue['dateDebut'] = $request->get('dateDebut');
            $searchValue['dateFinale'] = $request->get('dateFinale');
        }

        # champinat seul
        if ($request->get('championat_match') && !$request->get('pays_match')) {
            $championat = $request->get('championat_match');
            $dql .= " LEFT JOIN m.championat c";
            $where[] = " c.fullNameChampionat LIKE :championat ";
            $params["championat"] = '%' . $championat . '%';
            $searchValue['championat_match'] = $championat;
        }

        if ($request->get('pays_match') && !$request->get('championat_match')) {
            $pays = $request->get('pays_match');
            $dql .= " LEFT JOIN m.championat c";
            $where[] = " c.pays LIKE :pays ";
            $params['pays'] = "%" . $pays . "%";
            $searchValue['pays_match'] = $pays;
        }

        if ($request->get('championat_match') && $request->get('pays_match')) {
            $championat = $request->get('championat_match');
            $dql .= " LEFT JOIN m.championat c ";

            $pays = $request->get('pays_match');
            /*$dql .= " LEFT JOIN c.teamsPays tp";*/

            $where[] = " c.fullNameChampionat LIKE :championat ";
            $where[] = " c.pays LIKE :pays";

            $params['pays'] = "%" . $pays . "%";
            $params["championat"] = '%' . $championat . '%';

            $searchValue['championat_match'] = $championat;
            $searchValue['pays_match'] = $pays;

        }
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }

        if (empty($params)) {

            $queryMatchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
        } else {
            $queryMatchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params);
        }
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $queryMatchs, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        $matchs = $queryMatchs->getResult();
        //$this->em()->createQuery($dql)->setParameters($params);
        // var_dump($dql); die;
        $championatData = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
//        $country = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $dqli = "SELECT ch From ApiDBBundle:championat ch where ch.pays  is not null ";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dqli);
        $country = $query->getResult();
        $droitAdmin = $this->getDroitAdmin('Matchs');
        return $this->render('BackAdminBundle:Matchs:index.html.twig', array(
            'matchs' => $matchs,
            'championat' => $championatData,
            'country' => $country,
            'searchValue' => $searchValue,
            'droitAdmin' => $droitAdmin[0],
            'pagination' => $pagination
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


    public function editMatchsAction(Request $request, $id)
    {
        $currentMatchs = $this->getRepoFormId(self::ENTITY_MATCH, $id);
        $form = $this->formPost(self::FORM_MATCHS, $currentMatchs);

        $form->handleRequest($request);
        if ($form->isValid()) {

            $dir = $this->get('kernel')->getRootDir() . '/../web/images/Flag-foot/';
            $logoDomicile = $currentMatchs->getCheminLogoDomicile();
            $logoVisiteur = $currentMatchs->getCheminLogoVisiteur();
            if ($currentMatchs->getCheminLogoDomicile()) {
                $logoDomicileName = $currentMatchs->getTeamsDomicile()->getIdNameClub() . '.' . $logoDomicile->guessExtension();
                $logoDomicile->move($dir, $logoDomicileName);
                $currentMatchs->setCheminLogoDomicile($logoDomicileName);
            }
            if ($currentMatchs->getCheminLogoVisiteur()) {
                $logoVisiteurName = $currentMatchs->getTeamsVisiteur()->getIdNameClub() . '.' . $logoVisiteur->guessExtension();
                $logoVisiteur->move($dir, $logoVisiteurName);
                $currentMatchs->setCheminLogoVisiteur($logoVisiteurName);
            }
            $this->insert($currentMatchs, array('success' => 'success', 'error' => 'error'));

        }
        // roles
        $wsRoles = $this->get('roles.manager');
        $droitAdmin = $wsRoles->getDroitAdmin(self::DROITS);
        // list championat
        $championatData = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        // pays
        $dqli = "SELECT ch From ApiDBBundle:championat ch where ch.pays  is not null ";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dqli);
        $country = $query->getResult();

        return $this->render('BackAdminBundle:Matchs:edit_matchs.html.twig', array(
            'form' => $form->createView(),
            'matchs' => $currentMatchs,
            'championat' => $championatData,
            'country' => $country,
            /*
            'searchValue' => $searchValue,*/
            'droitAdmin' => $droitAdmin[0]
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

/*    public function addLotoFootAction(Request $request)
    {

        if ($request->get('numero')) {
            $numero = $request->get('numero');
        }
        if ($request->get('finvalidation')) {
            $finValidation = $request->get('finvalidation');
        }
        if ($request->get('lotofoot')) {
            $lotofoot = $request->get('lotofoot');
            if ($lotofoot == 'lf7') {
                $lotofoot7 = new LotoFoot7();
                $lotofoot7->setNumero($numero);
                $date = new \DateTime($finValidation);
                $lotofoot7->setFinValidation($date);
                $this->insert($lotofoot7, array('success' => 'success', 'error' => 'error'));
                return $this->redirectToRoute('list_loto_foot');
            }
            if ($lotofoot == 'lf15') {
                $lotofoot15 = new LotoFoot15();
                $lotofoot15->setNumero($numero);

                $lotofoot15->setFinValidation(new \DateTime($finValidation));
                $this->insert($lotofoot15, array('success' => 'success', 'error' => 'error'));
                return $this->redirectToRoute('list_loto_foot');
            }
        }


        return $this->render('BackAdminBundle:Matchs:add_loto_foot.html.twig', array(
            'droitAdmin' => $this->get('roles.manager')->getDroitAdmin('Matchs')[0]
        ));
    }*/

    public function addLotoFootAction(Request $request){

        $lotoFoot = new LotoFoot();
        $form = $this->formPost(self::FORM_LOTOFOOT, $lotoFoot);
        $form->handleRequest($request);

        if($form->isValid()){
            $finValidation = new \DateTime($form['finValidation']->getData());
            $lotoFoot->setFinValidation($finValidation);
            $this->insert($lotoFoot, array('success' => 'success' , 'error' => 'error') );
            return $this->redirectToRoute('list_loto_foot');
        }
        return $this->render('BackAdminBundle:Matchs:add_loto_foot.html.twig', array(
            'droitAdmin' => $this->get('roles.manager')->getDroitAdmin('Matchs')[0],
            'form' => $form->createView()
        ));
    }


    public function listLotofootAction(Request $request)
    {
        $session = new Session();
        
        $session->set("current_page","Loto_foot");
        $lotoFoot = $this->getAllEntity(self::ENTITY_LOTOFOOT);
        $droitAdmin = $this->get('roles.manager')->getDroitAdmin('Matchs');
        return $this->render('BackAdminBundle:Matchs:list_lotofoot.html.twig', array(
            'droitAdmin' => $droitAdmin[0],
            'lotoFoot' => $lotoFoot
        ));
    }

    public function listChampionatAction(Request $request)
    {

        $championat = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $wsDA = $this->get('roles.manager');
        $droitAdmin = $wsDA->getDroitAdmin('Matchs');
        return $this->render('BackAdminBundle:Matchs:list_championat.html.twig', array(
            'entities' => $championat,
            'droitAdmin' => $droitAdmin[0]
        ));
    }

    public function editChampionatAction(Request $request, $id)
    {
        $championat = $this->getRepoFormId(self::ENTITY_CHAMPIONAT, $id);

        $form = $this->formPost(self::FORM_CHAMPIONAT, $championat);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->insert($championat, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('list_championat');
        }
        return $this->render('@BackAdmin/Matchs/edit_championat.html.twig', array(
            'form' => $form->createView(),
            'championat' => $championat
        ));
    }

    public function editLotofootOldAction(Request $request, $id, $idLotoFoot)
    {

        if ($idLotoFoot == 7) {
            $currentLotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT7, $id);
        }
        if ($idLotoFoot == 15) {
            $currentLotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT15, $id);
        }

        if ($request->get('numero')) {
            $numero = $request->get('numero');
        }
        if ($request->get('finvalidation')) {
            $finValidation = $request->get('finvalidation');
        }
        if ($request->get('lotofoot')) {
            $lotofoot = $request->get('lotofoot');
            if ($lotofoot == 'lf7') {
                $lotofoot7 = $this->getRepoFormId(self::ENTITY_LOTOFOOT7, $id);
                $lotofoot7->setNumero($numero);
                $date = new \DateTime($finValidation);
                $lotofoot7->setFinValidation($date);
                $this->insert($lotofoot7, array('success' => 'success', 'error' => 'error'));
                return $this->redirectToRoute('list_loto_foot');
            }
            if ($lotofoot == 'lf15') {
                $lotofoot15 = $this->getRepoFormId(self::ENTITY_LOTOFOOT15, $id);
                $lotofoot15->setNumero($numero);

                $lotofoot15->setFinValidation(new \DateTime($finValidation));
                $this->insert($lotofoot15, array('success' => 'success', 'error' => 'error'));
                return $this->redirectToRoute('list_loto_foot');
            }
        }
        return $this->render('BackAdminBundle:Matchs:edit_lotofoot.html.twig', array(
            'currentLotoFoot' => $currentLotoFoot,
            'idLotoFoot' => $idLotoFoot,
            'id' => $id,
            'droitAdmin' => $this->get('roles.manager')->getDroitAdmin('Matchs')[0]
        ));
    }

    public function editLotofootAction(Request $request, $id){

        $currentLotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT, $id);
        $form = $this->formPost(self::FORM_LOTOFOOT, $currentLotoFoot);
        $form->handleRequest($request);
        //var_dump($currentLotoFoot->getFinValidation()->format('Y-m-d')); die;
        if($form->isValid()){
            $finValidation = new \DateTime($form['finValidation']->getData());
            $currentLotoFoot->setFinValidation($finValidation);
            $this->insert($currentLotoFoot, array('success' => 'success', 'error' => 'error'));

            return $this->redirectToRoute('list_loto_foot');
        }
        return $this->render('BackAdminBundle:Matchs:edit_lotofoot.html.twig', array(
            'currentLotoFoot' => $currentLotoFoot,
            'form' => $form->createView(),
            'finValidation' => $currentLotoFoot->getFinValidation()->format('Y-m-d'),
            'droitAdmin' => $this->get('roles.manager')->getDroitAdmin('Matchs')[0]
        ));
    }
    public function removeLotoFootAction($id, $idLotoFoot)
    {
        if ($idLotoFoot == 7) {
            $lotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT7, $id);
        }
        if ($idLotoFoot == 15) {
            $lotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT15, $id);
        }
        $this->remove($lotoFoot);
        return $this->redirectToRoute('list_loto_foot');
    }

    /**
     *
     * Ajouter des matchs dans un loto foot
     * @param Request $request
     * @param $id
     * @param $idLotoFoot
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addMatchInLotoFootAction(Request $request, $id)
    {

        $lotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT, $id);


        $championat = $this->getAllEntity(self::ENTITY_CHAMPIONAT);

        $country = $this->getRepo(self::ENTITY_CHAMPIONAT)->findCountryValid();

        $dql = "SELECT m, lf, ch from ApiDBBundle:Matchs m
              LEFT JOIN m.championat ch
              LEFT JOIN m.lotoFoot lf";
        $where = array();
        $where[] = " ch.isEnable = true";
        $params = array();
        $searchValue = array();

        if ($request->get('date_match')) {

            $dateMatch = $request->get('date_match');
            $where[] = " m.dateMatch BETWEEN :dateStart AND :dateEnd ";
            $dateStart = $dateMatch . ' 00:00:00';
            $dateEnd = $dateMatch . ' 23:59:59';
            $params["dateStart"] = $dateStart;
            $params["dateEnd"] = $dateEnd;
            $searchValue['date_match'] = $dateMatch;
        }

        # champinat seul
        if ($request->get('championat_match') && !$request->get('pays_match')) {

            $championat = $request->get('championat_match');
            //$dql .= " LEFT JOIN m.championat c";
            $where[] = " ch.fullNameChampionat LIKE :championat ";
            $params["championat"] = '%' . $championat . '%';
            $searchValue['championat_match'] = $championat;
        }
        if ($request->get('pays_match') && !$request->get('championat_match')) {
            $pays = $request->get('pays_match');
            //$dql .= " LEFT JOIN m.championat c  ";
            $where[] = " ch.pays LIKE :pays ";
            $params['pays'] = "%" . $pays . "%";
            $searchValue['pays_match'] = $pays;
        }
        if ($request->get('pays_match') && $request->get('championat_match')) {
            $pays = $request->get('pays_match');
            $championat = $request->get('championat_match');
            // $dql .= " LEFT JOIN m.championat c  ";
            $where[] = " ch.pays LIKE :pays AND ch.fullNameChampionat LIKE :championat";
            $params['pays'] = "%" . $pays . "%";
            $params["championat"] = '%' . $championat . '%';
            $searchValue['pays_match'] = $pays;
            $searchValue['championat_match'] = $championat;
        }

        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        $dql .= " ORDER BY m.dateMatch ASC, ch.rang asc,  m.id ASC ";
        if (empty($params)) {
            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->getResult();
        } else {
            $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        }
        if ($request->get('idMatch')) {
            $idMatch = $request->get('idMatch');
        }


        $data = explode('&', $request->getContent());
        $arrayData = array();
        //$i = 0;
        $idarray = array();
        foreach ($data as $vData) {
            if (substr($vData, 0, 7) == 'select_') {
                $arrayData[] = $vData;
                $idarray[] = str_replace('=on', '', str_replace('select_', '', $vData));
            }
        }

/*        if ($idLotoFoot == 7) {

            if (count($arrayData) < 7) {
                //  die('erreur < 7');
                echo("<script>alert('Erreur : Nombre de match Inférieur à <  7');</script>");
            }
            if (count($arrayData) > 7) {
                echo("<script>alert('Erreur : Nombre de match >  superieur à 7');</script>");
            }
            if (count($arrayData) <= 7) {
                if ($idarray) {
                    foreach ($idarray as $vId) {

                        $matchsEntity = $this->getRepoFormId(self::ENTITY_MATCH, $vId);
                        $matchsEntity->setLotoFoot7(null);
                        $this->get('doctrine.orm.entity_manager')->persist($matchsEntity);
                        $this->get('doctrine.orm.entity_manager')->flush();
                    }
                    foreach ($idarray as $vId) {
                        $matchsEntity = $this->getRepoFormId(self::ENTITY_MATCH, $vId);
                        $matchsEntity->setLotoFoot7($lotoFoot);
                        $this->get('doctrine.orm.entity_manager')->persist($matchsEntity);
                        $this->get('doctrine.orm.entity_manager')->flush();
                    }
                }


            }
        }

        if ($idLotoFoot == 15) {
            if (count($arrayData) == 14 or count($arrayData) == 15) {
                foreach ($idarray as $vId) {

                    $matchsEntity = $this->getRepoFormId(self::ENTITY_MATCH, $vId);
                    $matchsEntity->setLotoFoot15(null);
                    $this->get('doctrine.orm.entity_manager')->persist($matchsEntity);
                    $this->get('doctrine.orm.entity_manager')->flush();
                }
                if ($idarray) {
                    foreach ($idarray as $vId) {

                        $matchsEntity = $this->getRepoFormId(self::ENTITY_MATCH, $vId);
                        $matchsEntity->setLotoFoot15($lotoFoot);
                        $this->get('doctrine.orm.entity_manager')->persist($matchsEntity);
                        $this->get('doctrine.orm.entity_manager')->flush();
                    }
                }
            }
        }

        $nbMatchs7 = null;

        if ($idLotoFoot == 7) {
            $matchsCount = $this->getAllEntity(self::ENTITY_MATCH);
            $i = 0;
            foreach ($matchsCount as $vMatchs) {
                if ($vMatchs->getLotoFoot7()) {
                    $i = $i + 1;
                }
            }
            $nbMatchs7 = $i;
        }

        $nbMatchs15 = null;
        if ($idLotoFoot == 15) {
            $matchsCount = $this->getAllEntity(self::ENTITY_MATCH);
            $i = 0;
            foreach ($matchsCount as $vMatchs) {
                if ($vMatchs->getLotoFoot15()) {
                    $i = $i + 1;
                }
            }

            $nbMatchs15 = $i;
        }
        $champi = $this->getRepo(self::ENTITY_CHAMPIONAT)->findAll();*/
        // var_dump($matchs[0]->getLotoFoot7()); die;
        return $this->render('BackAdminBundle:Matchs:add_lotofoot.html.twig', array(
            'entity' => $lotoFoot,
            'championat' => $championat,
            'country' => $country,
            'pays' => $country,
            'matchs' => $matchs,
            'searchValue' => $searchValue,
            'droitAdmin' => $this->get('roles.manager')->getDroitAdmin('Matchs')[0]
        ));


    }


    private function getDroitAdmin($droit)
    {
        $droitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser(), 'droit' => $this->getRepo(self::ENTITY_DROIT)->findOneBy(array('fonctionnalite' => $droit))));
        return $droitAdmin;
    }

    public function removeMatchInLotoFootAction(Request $request, $idLotoFoot, $lotoId, $id)
    {

        $match = $this->getRepoFormId(self::ENTITY_MATCH, $id);
        if ($idLotoFoot == 7) {
            $lotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT7, $lotoId);
            $lotoFoot->removeMatch($match);
            $match->setLotoFoot7(null);
        }
        if ($idLotoFoot == 15) {
            $lotoFoot = $this->getRepoFormId(self::ENTITY_LOTOFOOT15, $lotoId);
            $lotoFoot->removeMatch($match);
            $match->setLotoFoot15(null);
        }
        $this->getEm()->persist($match);
        $this->getEm()->persist($lotoFoot);
        $this->getEm()->flush();
        //echo(count($lotoFoot->getMatchs()));die();
        return $this->redirectToRoute("add_match_loto_foot", array(
            "idLotoFoot" => $idLotoFoot,
            "id" => $lotoId
        ));
    }

    public function updateFromGoalApiAction(Request $request)
    {
        //var_dump(explode('&',$request->getContent())); die;
       /* $ArrayUrl = explode('&',$request->getContent());
        foreach($ArrayUrl as $k => $v){
            $arrayData[] = explode('=', $v);
        }
        var_dump($arrayData); die;*/
        $dateDebutGoalApi = "";
        if($request->get('dateDebutGoalApi')){
            $dateDebutGoalApi = $request->get('dateDebutGoalApi');
        }
        $dateFinaleGoalApi = "";
        if($request->get('dateFinaleGoalApi')){
            $dateFinaleGoalApi = $request->get('dateFinaleGoalApi');
        }
        $dateChampionatGoalApi = "";
        if($request->get('championat_goal_api')){
            $dateChampionatGoalApi = $request->get('championat_goal_api');
        }
        $params = array(
            'dateDebut' => $dateDebutGoalApi,
            'dateFinale' => $dateFinaleGoalApi,
            'championat_match' => $dateChampionatGoalApi
        );
        $dateDebutGoalApi = $request->request->get('dateDebutGoalApi');
        $dateFinaleGoalApi = $request->request->get('dateFinaleGoalApi');
        $championat_goal_api = $request->request->get('championat_goal_api');

        $command = new GoalApiMatchsManuelCommand();

        $command->setContainer($this->container);
        $input = new ArrayInput(array(
            '-ch' => $championat_goal_api,
            '-db' => $dateDebutGoalApi,
            '-df' => $dateFinaleGoalApi
        ));
        $output = new NullOutput();
        $resultCode = $command->run($input, $output);
        return $this->redirectToRoute('index_admin_match', $params);
    }
}
