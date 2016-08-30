<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ChampionatsController extends ApiController
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';

    /**
     * Ws, récupérer la liste des championnats qui ont des matchs
     */
    public function getListeChampionatWithMatchAction()
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->getListChampionatWithMatch();
        $result = array();
        if ($data) {
            foreach ($data as $k => $vData) {
                $result['list_championat'][] = array(
                    'nomChampionat' => $vData->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $vData->getChampionat()->getFullNameChampionat(),
                    'season' => $vData->getChampionat()->getSeason()
                );
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucun resultat n'a été trouvé";
        }
        return new JsonResponse($result);
    }

    /**
     * Ws, récupérer la liste des matchs pour le championnat sélectionné.(tri décroissant).
     *
     */
    public function postListeMatchsBySelectedChampionatAction(Request $request)
    {

        $championat = $request->request->get('championat');

        $data = $this->getRepo(self::ENTITY_MATCHS)->getListeMatchsBySelectedChampionat($championat);

        $result = array();
        if ($data) {
            foreach ($data as $vData) {
                $result['championat'] = $vData->getChampionat()->getFullNameChampionat();
                $result['list_match'][] = array(
                    'id' => $vData->getId(),
                    'dateMatch' => $vData->getDateMatch(),
                    'equipeDomicile' => $vData->getEquipeDomicile(),
                    'equipeVisiteur' => $vData->getEquipeVisiteur(),

                    'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/'.$vData->getCheminLogoDomicile().'.png',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/'.$vData->getCheminLogoVisiteur().'.png',// $vData->getTeamsVisiteur()->getLogo(),
                    'score' => $vData->getScore(),
                    'status' => $vData->getStatusMatch(),
                    'cote_pronostic_1' => $vData->getCot1Pronostic(),
                    'cote_pronostic_n' => $vData->getCoteNPronistic(),
                    'cote_pronostic_2' => $vData->getCote2Pronostic(),
                    'master_prono_1' => $vData->getMasterProno1(),
                    'master_prono_n' => $vData->getMasterPronoN(),
                    'master_prono_2' => $vData->getMasterProno2(),
                    'tempsEcoules' => $vData->getTempsEcoules(),
                    'live' => ($vData->getStatusMatch() == 'active') ? true : false,
                    'livetime' => ''
                );

            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }
        return new JsonResponse($result);
    }

    /**
     * GET liste pays with champonat with match
     */
    public function getListePaysWithChampionatWithMatchAction()
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->getListePaysWithChampionatWithMatch();
        $result = array();
        $dataName = array();

        if ($data) {
            foreach ($data as $k => $vData) {
                foreach ($vData->getChampionat()->getTeamsPays() as $ktp => $vDataTp) {
                    if (!in_array($vDataTp->getName(), $dataName)) {
                        $dataName[] = $vDataTp->getName();
                    }
                }

            }

            $result['pays'] = $dataName;
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }
        return new JsonResponse($result);
    }

    /**
     *
     * List liste des championat par pays si existe match
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getListeChampionatWithMatchByPaysAction(Request $request)
    {
        $pays = $request->request->get('pays');
        $data = $this->getRepo(self::ENTITY_MATCHS)->getListeChampionatWithMatchByPays($pays);
        $result = array();
        $dataName = array();
        if ($data) {
            $ancienName = "";

            foreach ($data as $vData) {

                if ($ancienName != $vData->getChampionat()->getNomChampionat()) {
                    $dataName[] = array(
                       'code' => $vData->getChampionat()->getNomChampionat(),
                        'fullName' =>$vData->getChampionat()->getFullNameChampionat()
                    );
                }
                $ancienName = $vData->getChampionat()->getNomChampionat();
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }
        $result['list_championat'] = $dataName;
        return new JsonResponse($result);
    }

    public function getListeMatchByChampionat(Request $request)
    {
        $championat = $request->request->get('championat');
        $data = $this->getRepo(self::ENTITY_MATCHS)->getListeMatchByChampionat($championat);
        $result[] = array();
        if ($data) {
            foreach ($data as $vData) {

            }
        }
    }


    public function getListePaysWithChampionatAvecMatchsAction(Request $request)
    {

        $dql = "SELECT m from ApiDBBundle:Matchs m
               LEFT JOIN m.championat ch
               LEFT JOIN ch.teamsPays tp
               WHERE CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat ";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
        $data = $query->getResult();
        if ($data) {
            $dataNamePays = array();
            $ancienNamePays = '';
            foreach ($data as $kData => $vData) {

                foreach ($vData->getChampionat()->getTeamsPays() as $kTeamsPays => $vTeamsPays) {
                    //$dataNamePays[] = $vTeamsPays->getName();
                    if (!in_array($vTeamsPays->getName(), $dataNamePays)) {
                        $dataNamePays[] = $vTeamsPays->getName();
                    }
                    /*if($ancienNamePays != $vTeamsPays->getName()){
                        $dataNamePays[] = $vTeamsPays->getName();
                    }
                    $ancienNamePays =  $vTeamsPays->getName();*/
                }

            }
            foreach ($dataNamePays as $kDataNamePays => $vDataNamePays) {
                $dqlChampinat = "SELECT m from ApiDBBundle:Matchs m
                       LEFT JOIN m.championat ch
                       LEFT JOIN ch.teamsPays tp
                       WHERE CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat
                       AND tp.name LIKE :namePays";
                $queryDqlChampionat = $this->get('doctrine.orm.entity_manager')->createQuery($dqlChampinat);
                $queryDqlChampionat->setParameter('namePays', $vDataNamePays);
                $dataChampionat = $queryDqlChampionat->getResult();

                if ($dataChampionat) {
                    //    var_dump($dataChampionat); die;
                    // $json['pays'][] = $vDataNamePays;
                    $ancienNameChampionat = '';
                    foreach ($dataChampionat as $kDataChampionat => $vDataChampionat) {
                        if ($ancienNameChampionat != $vDataChampionat->getChampionat()->getNomChampionat()) {
                            $json['pays'][$vDataNamePays]['list_championat'][] = $vDataChampionat->getChampionat()->getNomChampionat();
                        }
                        $ancienNameChampionat = $vDataChampionat->getChampionat()->getNomChampionat();

                    }
                }
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }

        $result['data'] = $json;
        return new JsonResponse($result);
    }
}
