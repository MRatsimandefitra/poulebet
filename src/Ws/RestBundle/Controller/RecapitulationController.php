<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\CommonBundle\Fixed\InterfaceResponseWs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RecapitulationController extends ApiController implements InterfaceDB
{
    public function postGetListRecapAction(Request $request){

        $isCombined = (bool) $request->request->get('isCombined');
        if($isCombined === NULL){
            return $this->noCombined();
        }
        $token = $request->request->get('token');
        if($isCombined === NULL){
            return $this->noToken();
        }
        $user  = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->noUser();
        }
        $this->getStateCombined($user->getId());
        $result = array();
        if($isCombined){
            $nbRecap = $this->getRepo(self::ENTITY_MATCHS)->findNbMatchsForRecapCombined($user->getId());
            if(!empty($nbRecap)){
                $count = 0;
                $championat = $this->getRepo(self::ENTITY_MATCHS)->findChampionatForRecapCombined($user->getId());
                if(!empty($championat)){
                    foreach($championat as $kChampionat => $itemsChampionat){

                        $result['list_championat'][] =array(
                            'idChampionat' =>  $itemsChampionat->getMatchs()->getChampionat()->getId(),
                            'nomChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                            'fullNameChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getFullNameChampionat(),
                        );
                    }
                }

                foreach($nbRecap as $itemsNbRecap){
                    $count = $count + 1;
                    $idMise[] = $itemsNbRecap->getIdMise();
                }
                $count = 0;
                foreach($idMise as $k => $itemsIdMise){
                    $matchs = array();
                    $count = $count + 1;
                    $ss = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForRecapCombined($user->getId(), $itemsNbRecap->getIdMise() );
                    if($ss){
                        foreach($ss as $k => $v){
                            $gain = $v->getGainPotentiel();
                            $miseTotal = $v->getMisetotale();
                            $matchs[] = array(
                                'idMatch' => $v->getMatchs()->getId(),
                                'dateMatch' => $v->getMatchs()->getDateMatch(),
                                'equipeDomicile' => $v->getMatchs()->getEquipeDomicile(),
                                'equipeVisiteur' => $v->getMatchs()->getEquipeVisiteur(),
                                'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $v->getMatchs()->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                                'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $v->getMatchs()->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                                'score' => $v->getMatchs()->getScore(),
                                'scoreDomicile' => substr($v->getMatchs()->getScore(), 0, 1),
                                'scoreVisiteur' => substr($v->getMatchs()->getScore(), -1, 1),
                                'status' => $v->getMatchs()->getStatusMatch(),
                                'tempsEcoules' => $v->getMatchs()->getTempsEcoules(),
                                'live' => ($v->getMatchs()->getStatusMatch() == 'active') ? true : false,
                                'master_prono_1' => $v->getMatchs()->getMasterProno1(),
                                'master_prono_n' => $v->getMatchs()->getMasterPronoN(),
                                'master_prono_2' => $v->getMatchs()->getMasterProno2(),
                                'cote_pronostic_1' => $v->getMatchs()->getCot1Pronostic(),
                                'cote_pronostic_n' => $v->getMatchs()->getCoteNPronistic(),
                                'cote_pronostic_2' => $v->getMatchs()->getCote2Pronostic(),
                                'voted_equipe' => $v->getVote()
                            );
                        }
                    }
                    $result['list_mise'][] = array(
                        'miseId' =>$itemsIdMise,
                        'gainsPotentiel' => $gain,
                        'miseTotal' => $miseTotal,
                        'matchs' => $matchs,
                       // 'state' => $this->getStateCombined()
                    );
                   /* $resultMatchs[$itemsIdMise]['gain'] = $gain;
                    $resultMatchs[$itemsIdMise]['miseTotal'] = $miseTotal;*/
                   // $resultMatchs[$itemsIdMise]['matchs'] = $matchs;
                    $resultMatchs[$itemsIdMise]['gagnant'] = "";
                }

              //  $result['details'] = $resultMatchs;
               /* if(!empty($resultMatchs)){
                    foreach($resultMatchs as $itemsResultMatchs){

                        $iResultMatchs[] = array(
                            'idMatchs' => $itemsResultMatchs->getMatchs()->getId(),
                            'dateMatch' => $itemsResultMatchs->getMatchs()->getDateMatch(),
                            'equipeDomicile' => $itemsResultMatchs->getMatchs()->getEquipeDomicile(),
                            'equipeVisiteur' => $itemsResultMatchs->getMatchs()->getEquipeVisiteur(),
                            'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsResultMatchs->getMatchs()->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                            'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsResultMatchs->getMatchs()->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                            'score' => $itemsResultMatchs->getMatchs()->getScore(),
                            'scoreDomicile' => substr($itemsResultMatchs->getMatchs()->getScore(), 0, 1),
                            'scoreVisiteur' => substr($itemsResultMatchs->getMatchs()->getScore(), -1, 1),
                            'status' => $itemsResultMatchs->getMatchs()->getStatusMatch(),
                            'tempsEcoules' => $itemsResultMatchs->getMatchs()->getTempsEcoules(),
                            'live' => ($itemsResultMatchs->getMatchs()->getStatusMatch() == 'active') ? true : false,
                            'master_prono_1' => $itemsResultMatchs->getMatchs()->getMasterProno1(),
                            'master_prono_n' => $itemsResultMatchs->getMatchs()->getMasterPronoN(),
                            'master_prono_2' => $itemsResultMatchs->getMatchs()->getMasterProno2(),
                            'cote_pronostic_1' => $itemsResultMatchs->getMatchs()->getCot1Pronostic(),
                            'cote_pronostic_n' => $itemsResultMatchs->getMatchs()->getCoteNPronistic(),
                            'cote_pronostic_2' => $itemsResultMatchs->getMatchs()->getCote2Pronostic(),
                            'idMise' => $itemsResultMatchs->getIdMise(),
                            'voted_equipe' => $itemsResultMatchs->getVote(),
                            'gainPotentiel' => $itemsResultMatchs->getGainPotentiel(),
                            'miseTotal' => $itemsResultMatchs->getMisetotale(),
                            'idChampionat' => $itemsResultMatchs->getMatchs()->getChampionat()->getId(),
                        );
                    }
                    $result['vote_matchs'][] = $iResultMatchs;
                }*/
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "success";
                return new JsonResponse($result);
            }else{
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun resultat trouve";
                return new JsonResponse($result);
            }
        }else{
            // championat
            $championat = $this->getRepo(self::ENTITY_MATCHS)->findChampionatVoteSimple($user->getId());
           // var_dump($championat); die;
            if($championat){
                foreach($championat as $kChampionat => $itemsChampionat){
                    $result['list_championat'][] = array(
                        'idChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getId(),
                        'nomChampionat' =>$itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                        'fullNameChampionat' =>$itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                    );
                }
            }
            // matchs
            $nbRecap = $this->getRepo(self::ENTITY_MATCHS)->findNbMatchsVoteSimple($user->getId());

            if(!empty($nbRecap)){
                foreach($nbRecap as $k => $vItems){
                    $result['list_match'][] = array(
                        'idMatch' => $vItems->getMatchs()->getId(),
                        'dateMatch' => $vItems->getMatchs()->getDateMatch(),
                        'equipeDomicile' => $vItems->getMatchs()->getEquipeDomicile(),
                        'equipeVisiteur' => $vItems->getMatchs()->getEquipeVisiteur(),
                        'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $vItems->getMatchs()->getCheminLogoDomicile() . '.png',// $vItemsData->getTeamsDomicile()->getLogo(),
                        'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $vItems->getMatchs()->getCheminLogoVisiteur() . '.png',// $vItemsData->getTeamsVisiteur()->getLogo(),
                        'score' => $vItems->getMatchs()->getScore(),
                        'scoreDomicile' => substr($vItems->getMatchs()->getScore(), 0, 1),
                        'scoreVisiteur' => substr($vItems->getMatchs()->getScore(), -1, 1),
                        'status' => $vItems->getMatchs()->getStatusMatch(),
                        'tempsEcoules' => $vItems->getMatchs()->getTempsEcoules(),
                        'live' => ($vItems->getMatchs()->getStatusMatch() == 'active') ? true : false,
                        'master_prono_1' => $vItems->getMatchs()->getMasterProno1(),
                        'master_prono_n' => $vItems->getMatchs()->getMasterPronoN(),
                        'master_prono_2' => $vItems->getMatchs()->getMasterProno2(),
                        'cote_pronostic_1' => $vItems->getMatchs()->getCot1Pronostic(),
                        'cote_pronostic_n' => $vItems->getMatchs()->getCoteNPronistic(),
                        'cote_pronostic_2' => $vItems->getMatchs()->getCote2Pronostic(),
                        'gainsPotentiel' => $vItems->getGainPotentiel(),
                        'miseTotal' => $vItems->getMisetotale(),
                        'state' => $this->getMatchsState($vItems->getId()),
                        'idChampionat' => $vItems->getMatchs()->getChampionat()->getId(),
                        'voted_equipe' => $vItems->getVote()
                    );
                }
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Success";
                return new JsonResponse($result);
            }else{
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun donne disponible";
                return new JsonResponse($result);
            }


        }

    }


    private function noToken(){
        $result = $this->no();
        $result['message'] = " Token doit être specifie";
        return new JsonResponse($result);
    }
    private function noCombined(){
        $result = $this->no();
        $result['message'] = " IsCombined doit être specifie";
        return new JsonResponse($result);
    }
    private function no(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        return $result;
    }
    private function noUser(){
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Aucun utilisateur compatible avec le token";
        return new JsonResponse($result);
    }
    private function getMatchsState($idMatch){
        $matchs = $this->getObjectRepoFrom(self::ENTITY_MATCHS, array('id' => $idMatch));
        if($matchs){
            $state = $matchs->getStatusMatchs();
            return $state;
        }
        return null;
    }
    private function getStateCombined($utilisateur){
        $matchsVote = $this->getRepo(self::ENTITY_MATCHS)->findStateForCombined($utilisateur);

        if($matchsVote){
            foreach($matchsVote as $kMatchsVote => $itemsMatchsVote){
                //var_dump($itemsMatchsVote->getMatchs()->getId()); die;
                $matchs = $this->getRepoFrom(self::ENTITY_MATCHS, array('id' => $itemsMatchsVote->getMatchs()->getId() ));
                if(!empty($matchs)){
                    //foreach($matchs as $kmatchs)
                }
            }
        }
    }
}

