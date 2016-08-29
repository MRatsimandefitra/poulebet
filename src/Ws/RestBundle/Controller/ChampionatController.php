<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ChampionatController extends ApiController
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';

    public function getChampionatWithMatchAction(){
        $matchsWithChampionat = $this->getRepo(self::ENTITY_MATCHS)->findChampionatWithMatch();
        $result = array();

        foreach($matchsWithChampionat as $vData){
            $result[] = array(
                'id' => $vData->getId(),
                'dateMatch' => $vData->getDateMatch(),
                'equipeDomicile' => $vData->getEquipeDomicile(),
                'equipeVisiteur' => $vData->getEquipeVisiteur(),
                'cheminLogoDomicile' => $this->get('kernel')->getRootDir().'/../web/upload/admin/flag/' .$vData->getCheminLogoDomicile(),
                'cheminLogoVisiteur' => $this->get('kernel')->getRootDir().'/../web/upload/admin/flag/' .$vData->getCheminLogoDomicile(),
                'score' => $vData->getScore(),
                'resultatDomicile' => $vData->getResultatDomicile(),
                'resultatVisiteur' => $vData->getResultatVisiteur(),
                'cotePronostic1' => $vData->getCot1Pronostic(),
                'cotePronostic2' => $vData->getCote2Pronostic(),
                'cotePronosticN' => $vData->getCoteNPronistic(),
                'masterProno1' => $vData->getMasterProno1(),
                'masterProno2' => $vData->getMasterProno2(),
                'masterPronoN' => $vData->getMasterPronoN(),
                'tempsEcoules' => $vData->getTempsEcoules()

            );
        }
        if(!empty($result)){
            $result['code_error'] = 0;
            $result['message'] = "get Data réussi avec success";
        }else{
            $result['code_error'] = 2;
            $result['message'] = "Aucune donné n'a été trouvé";
        }
        return  new JsonResponse($result);
    }

    /**
     * LISTE DES CHAMPIONATS VALIDE RELIER A UN MATCH
     * @return JsonResponse
     */
    public function getChampionatWithNationalMatchAction(){
        $mathsWithNational = $this->getRepo(self::ENTITY_MATCHS)->findChampionatWitwMatchValide();

        $result = array();
        if($mathsWithNational){

            foreach($mathsWithNational as $k => $vDataChampionat){
                    $result[$k] = array(
                        'idChampionat' => $vDataChampionat->getChampionat()->getNomchampionat(),
                        'fullNameChampionat' => $vDataChampionat->getChampionat()->getFullNameChampionat()
                    );
            }
            $result['code_error'] = 0;
            $result['message'] = 'Success';
        }
        if(!$mathsWithNational){
            $result['code_error'] = 4;
            $result['message'] = "Aucun championat n'a été trouvé";
        }
        return new JsonResponse($result);
    }

    /**
     * LISTE DES CHAMPIONNAT PAR PAYS SELECTIONNER
     */
    public function getListChampionatByCountryAction($pays){
        $this->getRepo(self::ENTITY_CHAMPIONAT)->getChampionatParPays();

    }

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
    public function getListeMatchsBySelectedChampionatAction(Request $request)
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
                    'logoDomicile' => 'logoDomicile',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => 'logoVisiteur',// $vData->getTeamsVisiteur()->getLogo(),
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
     * Ws, récupérer la liste des paysqui ont des championnats nationaux avec des matchs
     */
    public function getListePaysWithChampionatWithMatchsAction()
    {

        $data = $this->getRepo(self::ENTITY_MATCHS)->getListePaysWithChampionatWithMatchs();
        $result = array();
        if ($data) {

            foreach ($data as $vData) {
                //  var_dump($vData);
                //    die;
            }
            $result['code_erreur'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
        } else {
            $result['code_erreur'] = 4;
            $result['message'] = "Aucune donné n'a été trouvé";
        }
        return new JsonResponse($result);
    }

    /**
     * Ws récupérer la liste des championnats nationaux pour le pays sélectionné
     */
    public function getListChampionatBySelectedPaysAction(Request $request)
    {
        $pays = $request->request->get('pays');
        $data = $this->getRepo(self::ENTITY_MATCHS)->findListeChampionatNationauxByPays($pays);
        $result = array();
        if ($data) {

            foreach ($data as $k => $vData) {
                $result['pays'][] = $vData->getTeamsPays()[$k]->getName();
                $result['list_championat'][] = array(
                    'nomChampionat' => $vData->getFullNameChampionat(),
                );
            }
            $result['code_error'] = 0;
            $result['message'] = "sucess";
            $result['success'] = true;
            $result['error'] = false;
        } else {

            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucun donné n'a été trouvé";
        }

        return new JsonResponse($result);

    }


    public function findListPaysWithChampionatWithMatchsAction()
    {
        $data = $this->getRepo(self::ENTITY_CHAMPIONAT)->findListPaysWithChampionatWithMatchs();

        $result = array();

        if ($data) {
            foreach ($data as $k => $vData) {
                if ($vData->getChampionat()->getTeamsPays()[$k]->getName()) {
                    $result['pays'][] = $vData->getChampionat()->getTeamsPays()[$k]->getName();
                }
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucun résultat n'a été trouvé";
        }

        return new JsonResponse($result);
    }

    /**
     *
     * LIST PAYS
     */
    public function findListPaysWithChampionatAction()
    {
        $dql = "SELECT ch from ApiDBBundle:Championat ch
               LEFT JOIN ch.teamsPays tp ";

    }
}
