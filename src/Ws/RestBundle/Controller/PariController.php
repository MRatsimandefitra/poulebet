<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\CommonBundle\Fixed\InterfacePari;
use Api\DBBundle\Entity\VoteUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PariController extends ApiController implements InterfaceDB
{
    public function postGetAllMathsAction(Request $request){

        $date = $request->request->get('date');
        $championatWs = $request->request->get('championat');
        $token = $request->request->get('token');
        $isCombined = (bool) $request->request->get('isCombined');
        $result = array();

        if($isCombined){
            if(!$token){
                $this->noToken();
            }

            $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
            if(!$user){
               $this->noUser();
            }
            $credit = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('utilisateur' => $user));
            $championatR = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, true);
            if($championatR){
                foreach($championatR as $kChampionat => $itemsChampionat){
                    $result['list_championat'][] = array(
                        'idChampionat' => $itemsChampionat->getChampionat()->getId(),
                        'nomChampionat' => $itemsChampionat->getChampionat()->getNomChampionat(),
                        'fullNameChampionat' => $itemsChampionat->getChampionat()->getFullNameChampionat()
                    );
                }
            }
            // credut
            if($credit){
                foreach($credit as $kCredit => $itemsCredit){
                    $result['credit'] = array(
                        'soldeCredit' => (!$credit) ? $itemsCredit->getSoldeCredit(): 0,
                        'gainPotentiel' => ''
                    );
                }

            }else{
                $result['credit'] = array(
                    'soldeCredit' =>  0,
                    'gainPotentiel' => 0,
                );
            }
            // matchs
            $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs);
            if($matchs){
                foreach($matchs as $kMatchs => $matchsItems){

                    $result['list_matchs'][] = array(
                        'id' => $matchsItems->getId(),
                        'dateMatch' => $matchsItems->getDateMatch(),
                        'equipeDomicile' => $matchsItems->getEquipeDomicile(),
                        'equipeVisiteur' => $matchsItems->getEquipeVisiteur(),
                        'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $matchsItems->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                        'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $matchsItems->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                        'score' => $matchsItems->getScore(),
                        'scoreDomicile' => substr($matchsItems->getScore(), 0, 1),
                        'scoreVisiteur' => substr($matchsItems->getScore(), -1, 1),
                        'status' => $matchsItems->getStatusMatch(),
                        'tempsEcoules' => $matchsItems->getTempsEcoules(),
                        'live' => ($matchsItems->getStatusMatch() == 'active') ? true : false,
                        'masterProno1' => $matchsItems->getMasterProno1(),
                        'masterPronoN' => $matchsItems->getMasterPronoN(),
                        'masterProno2' => $matchsItems->getMasterProno2(),
                        'cotePronostic1' => $matchsItems->getCot1Pronostic(),
                        'cotePronosticN' => $matchsItems->getCoteNPronistic(),
                        'cotePronostic2' => $matchsItems->getCote2Pronostic(),
                        /*'gainsPotentiel' => $this->getGainsPotentiel($user->getId(), $matchsItems->getId()),
                        'miseTotal' => $this->getMiseTotal($user->getId(), $matchsItems->getId()),*/
                        'idChampionat' => $matchsItems->getChampionat()->getId()
                    );

                }
                $result['code_error'] = 0;
                $result['success'] = true;
                $result['error'] = false;
                $result['message'] = "Success";

            }else{
                /*$result['code_error'] = 0;
                $result['success'] = true;
                $result['error'] = false;
                $result['message'] = "Success";*/
            }
            //return new JsonResponse($result);

            return new JsonResponse($result);
        }else{

            if(!$token){
                $this->noToken();
            }
            $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
            if(!$user){
                $this->noUser();
            }
            $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs);
            $championat = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, true);



            if($championat){
                foreach($championat as $kChampionat => $itemsChampionat){

                    $result['list_championat'][] = array(
                        'idChampionat' => $itemsChampionat->getChampionat()->getId(),
                        'nomChampionat' => $itemsChampionat->getChampionat()->getNomChampionat(),
                        'fullNameChampionat' => $itemsChampionat->getChampionat()->getFullNameChampionat()
                    );
                }
            /*    $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "success";*/
            }else{
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun championat";
                return new JsonResponse($result);
            }

            if($matchs){
                foreach($matchs as $kMatchs => $matchsItems){
                    //var_dump($matchsItems->getId()); die;
                    $result['list_matchs'][] = array(
                        'id' => $matchsItems->getId(),
                        'dateMatch' => $matchsItems->getDateMatch(),
                        'equipeDomicile' => $matchsItems->getEquipeDomicile(),
                        'equipeVisiteur' => $matchsItems->getEquipeVisiteur(),
                        'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $matchsItems->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                        'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $matchsItems->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                        'score' => $matchsItems->getScore(),
                        'scoreDomicile' => substr($matchsItems->getScore(), 0, 1),
                        'scoreVisiteur' => substr($matchsItems->getScore(), -1, 1),
                        'status' => $matchsItems->getStatusMatch(),
                        'tempsEcoules' => $matchsItems->getTempsEcoules(),
                        'live' => ($matchsItems->getStatusMatch() == 'active') ? true : false,
                        'masterProno1' => $matchsItems->getMasterProno1(),
                        'masterPronoN' => $matchsItems->getMasterPronoN(),
                        'masterProno2' => $matchsItems->getMasterProno2(),
                        'cotePronostic1' => $matchsItems->getCot1Pronostic(),
                        'cotePronosticN' => $matchsItems->getCoteNPronistic(),
                        'cotePronostic2' => $matchsItems->getCote2Pronostic(),
                        'gainsPotentiel' => '', /*$this->getGainsPotentiel($user->getId(), $matchsItems->getId()),*/
                        'miseTotal' => '', // $this->getMiseTotal($user->getId(), $matchsItems->getId()),
                        'idChampionat' => $matchsItems->getChampionat()->getId()
                    );
                }
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "success";
            }else{
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun matchs";
            }

            return new JsonResponse($result);
        }

    }

    private function getGainsPotentiel($idUser, $idMatchs){
        $voteUtilisateur = $this->getObjectRepoFrom(self::ENTITY_MATCHS)->findGains($idUser, $idMatchs);
        if(!$voteUtilisateur){
            return null;
        }
        return $voteUtilisateur->getGainPotentiel();
    }
    private function getMiseTotal($idUser, $idMatchs){
        $voteUtilisateur = $this->getObjectRepoFrom(self::ENTITY_MATCHS)->findGains($idUser, $idMatchs);
        if(!$voteUtilisateur){
            return null;
        }
        return $voteUtilisateur->getMiseTotal();
    }
    private function noUser(){
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Aucun utilisateur";
        return new JsonResponse($result);
    }

    private function noToken(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le token utilisateur doit être spécifié";
        return new JsonResponse($result);
    }
    public function postGetNbPouletAction(Request $request){
        $token = $request->request->get('token');
        $result = array();
        if(!$token){
            $result['code_error'] = 2;
            $result['error'] = true;
            $result['success'] = false;
            $result['message'] = "Le parametre token doit être specifie";
            return new JsonResponse($result);
        }
        $currentUser= $this->getRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$currentUser){
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucun utilisateur en cours";
            return new JsonResponse($result);
        }
        $credit = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('utilisateur' => $currentUser));
        if(!$credit){
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucun Credit pour utilisateur";
            return new JsonResponse($result);
        }
        if($credit){
            foreach($credit as $kCredit => $itemCredit){
                $result['credit'][] = array(
                    'soldeCredit' => $itemCredit->getSoldeCredit()
                );
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
            return new JsonResponse($result);

        }
    }

    public function insertPariAction(Request $request){

        $isCombined = $request->request->get('isCombined');
        $token  = $request->request->get('token');
        $user=  $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if($isCombined){
            $jsonDataCombined =  $request->request->get('jsonDataCombined');
            $gainsPotentiel = $request->request->get('gainPotentiel');
            $data = json_decode($jsonDataCombined, true);
            //var_dump($data['matchs']); die;
            if(!empty($data['matchs'])){
                foreach($data['matchs'] as $kMatchs => $itemsMatchs){
                    $idMatchs = $itemsMatchs['id'];
                    $voteMatchs = $itemsMatchs['vote'];
                    $matchs = $this->getObjectRepoFrom(self::ENTITY_MATCHS, array('id' => $idMatchs));
                    if(!$matchs){
                        die('pas de matchs');
                    }
                    $vu = new VoteUtilisateur();
                    $vu->setVote($voteMatchs);
                    $vu->setMatchs($matchs);
                    $vu->setUtilisateur($user);
                    $vu->setGainPotentiel($gainsPotentiel);

                    $this->getEm()->persist($vu);
                    $this->getEm()->flush();
                }
            }

        }
        else
        {

        }
    }
}
