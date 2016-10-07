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
                    foreach($ss as $k => $v){
                        $matchs[] = array(
                            'idMatchs' => $v->getMatchs()->getId(),
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
                        );
                    }
                    $resultMatchs[$itemsIdMise] = $matchs;
                  //  $resultMatchs[] = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForRecapCombined($user->getId(), $itemsNbRecap->getIdMise() );
                   /* foreach($resultMatchs as $itemsResultMatch){
                        $sousResultMatchs[] = array(
                            'idMatchs' => $itemsResultMatch->getMatchs()->getId(),
                            'dateMatch' => $itemsResultMatch->getMatchs()->getDateMatch(),
                            'equipeDomicile' => $itemsResultMatch->getMatchs()->getEquipeDomicile(),
                            'equipeVisiteur' => $itemsResultMatch->getMatchs()->getEquipeVisiteur(),
                            'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsResultMatch->getMatchs()->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                            'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsResultMatch->getMatchs()->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                            'score' => $itemsResultMatch->getMatchs()->getScore(),
                            'scoreDomicile' => substr($itemsResultMatch->getMatchs()->getScore(), 0, 1),
                            'scoreVisiteur' => substr($itemsResultMatch->getMatchs()->getScore(), -1, 1),
                            'status' => $itemsResultMatch->getMatchs()->getStatusMatch(),
                            'tempsEcoules' => $itemsResultMatch->getMatchs()->getTempsEcoules(),
                            'live' => ($itemsResultMatch->getMatchs()->getStatusMatch() == 'active') ? true : false,
                            'master_prono_1' => $itemsResultMatch->getMatchs()->getMasterProno1(),
                            'master_prono_n' => $itemsResultMatch->getMatchs()->getMasterPronoN(),
                            'master_prono_2' => $itemsResultMatch->getMatchs()->getMasterProno2(),
                            'cote_pronostic_1' => $itemsResultMatch->getMatchs()->getCot1Pronostic(),
                            'cote_pronostic_n' => $itemsResultMatch->getMatchs()->getCoteNPronistic(),
                            'cote_pronostic_2' => $itemsResultMatch->getMatchs()->getCote2Pronostic(),
                        );
                    }*/

                }
                $result['vote'] = $resultMatchs;
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
                return new JsonResponse($result);
            }else{

            }
            //$matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForRecap($user);

            return new JsonResponse($result);
        }else{


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
        $result['success'] = true;
        return $result;
    }
}

