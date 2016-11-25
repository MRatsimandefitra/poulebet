<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\CommonBundle\Fixed\InterfacePari;
use Api\DBBundle\Entity\Matchs;
use Api\DBBundle\Entity\MvtCredit;
use Api\DBBundle\Entity\NotificationRecapitulation;
use Api\DBBundle\Entity\VoteUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class PariController extends ApiController implements InterfaceDB
{
    /**
     * Ws, récupérer la les matchs pour les paris
     * @ApiDoc(
     *  description="Ws, récupérer la liste les matchs pour les paris",
     *   parameters = {
     *          {"name" = "date", "dataType"="date" ,"required"=false, "description"= "date of matchs lets mec ...."},
     *          {"name" = "championat", "dataType"="string" ,"required"=false, "description"= "Identifiant championat ex: eng_pl"},
     *          {"name" = "isCombined", "dataType"="bool" ,"required"=false, "description"= "Combiné ou non"}
     *      }
     * )
     */
    public function postGetAllMathsAction(Request $request)
    {

        $date = $request->get('date');
        $championatWs = $request->get('championat');
        $token = $request->get('token');

        if (!$token) {
            return $this->noToken();
        }
        $isCombined = (bool)$request->request->get('isCombined');
        if ($isCombined === NULL) {
            return $this->noCombined();
        }

        $result = array();

        if ($isCombined) {

            $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
            if (!$user) {
                return $this->noUser();
            }
            $credit = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());

            $concourEncour = $this->getRepo(self::ENTITY_MATCHS)->findIdConcourByDate();
            $idConcour = $concourEncour[0]->getId();
            $championatR = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, true, $idConcour);
            if (!empty($championatR)) {

                foreach ($championatR as $kChampionat => $itemsChampionat) {
                    $result['list_championat'][] = array(
                        'idChampionat' => $itemsChampionat->getChampionat()->getId(),
                        'nomChampionat' => $itemsChampionat->getChampionat()->getNomChampionat(),
                        'fullNameChampionat' => $itemsChampionat->getChampionat()->getFullNameChampionat()
                    );
                }
                if (!empty($credit)) {
                    $idLast = $credit[0][1];
                    $solde = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
                    foreach ($solde as $kCredit => $itemsCredit) {
                        $result['solde'] = $itemsCredit->getSoldeCredit();
                    }
                } else {
                    $result['solde'] = 0;
                }
            }
            // credit

            $nbRecapTotal = $this->getRepo(self::ENTITY_MATCHS)->findNbRecapMatchsSimpleAndCombined($user->getId());
            // matchs
            $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, null, $idConcour);

            if ($matchs) {
                foreach ($matchs as $kMatchs => $matchsItems) {

                    $result['list_matchs'][] = array(
                        'idMatch' => $matchsItems->getId(),
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
                        'master_prono_1' => $matchsItems->getMasterProno1(),
                        'master_prono_n' => $matchsItems->getMasterPronoN(),
                        'master_prono_2' => $matchsItems->getMasterProno2(),
                        'cote_pronostic_1' => $matchsItems->getCot1Pronostic(),
                        'cote_pronostic_n' => $matchsItems->getCoteNPronistic(),
                        'cote_pronostic_2' => $matchsItems->getCote2Pronostic(),
                        'idChampionat' => $matchsItems->getChampionat()->getId(),
                        'noPari'=> $this->getPariFroSimple($matchsItems->getId()),
                    );
                    $cote1 = $matchsItems->getCot1Pronostic();
                    $coteN = $matchsItems->getCoteNPronistic();
                    $cote2 = $matchsItems->getCote2Pronostic();
                    $rowCote = array(
                        $cote1, $coteN, $cote2
                    );
                    $resultCote[] = max($rowCote);

                }
                arsort($resultCote);
                $countCote = 0;
                $value = 1;
                foreach($resultCote as $kResultCote => $itemsResultCode){
                    $countCote = $countCote +  1;
                    if($countCote <= 8){
                        $value = $value * $itemsResultCode;
                    }
                }
                $pub = $this->getObjectRepoFrom(self::ENTITY_PUB, array('isPopup' => false));
                if(is_object($pub) && $pub){
                    $result['banniere'] = 'http://dplb.arkeup.com/upload/admin/publicite/'.$pub->getCheminPub();
                }

                $result['gain_potentiel_max'] = round($value);
                $result['itemTotal'] = $nbRecapTotal;
                $result['code_error'] = 0;
                $result['success'] = true;
                $result['error'] = false;
                $result['message'] = "Success";

            } else {
                $result['code_error'] = 4;
                $result['success'] = true;
                $result['error'] = false;
                $result['message'] = "Aucun resultat";
            }
            //return new JsonResponse($result);

            return new JsonResponse($result);
        } else {

            if (!$token) {
                return $this->noToken();
            }
            $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
            if (!$user) {
                return $this->noUser();
            }
            $nbRecapTotal = $this->getRepo(self::ENTITY_MATCHS)->findNbRecapMatchsSimpleAndCombined($user->getId());
            $concourEncour = $this->getRepo(self::ENTITY_MATCHS)->findIdConcourByDate();

            if(!$concourEncour){
                return $this->noConcour();
            }

            $idConcour = $concourEncour[0]->getId();
            $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, null, $idConcour);
            $userId = $user->getId();

            $matchsVote = $this->getRepo(self::ENTITY_MATCHS)->findMatchVote($userId, $date, $championatWs);

            $championat = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, true, $idConcour);

            if ($championat) {
                foreach ($championat as $kChampionat => $itemsChampionat) {

                    $result['list_championat'][] = array(
                        'idChampionat' => $itemsChampionat->getChampionat()->getId(),
                        'nomChampionat' => $itemsChampionat->getChampionat()->getNomChampionat(),
                        'fullNameChampionat' => $itemsChampionat->getChampionat()->getFullNameChampionat()
                    );
                }
            } else {
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun championat";
                return new JsonResponse($result);
            }

            //
            /*if ($matchsVote) {
                foreach ($matchsVote as $kMatchsVote => $itemsMatchVote) {
                    $result['list_matchs'][] = array(
                        'idVote' => $itemsMatchVote->getId(),
                        'idMatch' => $itemsMatchVote->getMatchs()->getId(),
                        'dateMatch' => $itemsMatchVote->getMatchs()->getDateMatch(),
                        'equipeDomicile' => $itemsMatchVote->getMatchs()->getEquipeDomicile(),
                        'equipeVisiteur' => $itemsMatchVote->getMatchs()->getEquipeVisiteur(),
                        'voted_equipe' => $itemsMatchVote->getVote(),
                        'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchVote->getMatchs()->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                        'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchVote->getMatchs()->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                        'score' => $itemsMatchVote->getMatchs()->getScore(),
                        'scoreDomicile' => substr($itemsMatchVote->getMatchs()->getScore(), 0, 1),
                        'scoreVisiteur' => substr($itemsMatchVote->getMatchs()->getScore(), -1, 1),
                        'status' => $itemsMatchVote->getMatchs()->getStatusMatch(),
                        'tempsEcoules' => $itemsMatchVote->getMatchs()->getTempsEcoules(),
                        'live' => ($itemsMatchVote->getMatchs()->getStatusMatch() == 'active') ? true : false,
                        'master_prono_1' => $itemsMatchVote->getMatchs()->getMasterProno1(),
                        'master_prono_n' => $itemsMatchVote->getMatchs()->getMasterPronoN(),
                        'master_prono_2' => $itemsMatchVote->getMatchs()->getMasterProno2(),
                        'cote_pronostic_1' => $itemsMatchVote->getCote1(),
                        'cote_pronostic_n' => $itemsMatchVote->getCoteN(),
                        'cote_pronostic_2' => $itemsMatchVote->getCote2(),
                        'gainsPotentiel' => $this->getGainsPotentiel($user->getId(), $itemsMatchVote->getMatchs()->getId(), $itemsMatchVote->getId()),
                        'miseTotal' => $this->getMiseTotal($user->getId(), $itemsMatchVote->getMatchs()->getId(), $itemsMatchVote->getId()),
                        'jouer' => true,
                        'noPari' => $this->getPariFroSimple($itemsMatchVote->getMatchs()->getId()),
                        'idChampionat' => $itemsMatchVote->getMatchs()->getChampionat()->getId(),

                    );

                }
            }else{
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun matchs";
            }*/
            $resultMatch = array();
            if ($matchs) {

                foreach ($matchs as $kMatchs => $matchsItems) {
                   // var_dump(); die;
                  //  if (!$this->getJouer($matchsItems->getId(), $user->getId())) {
                    if($this->isMatchsVoted($matchsItems->getId(), $user->getId())){

                        $nbVote = $this->getNbVote($matchsItems->getId(), $user->getId());
                        $resultMatch[] = array(
                            'idMatch' => $matchsItems->getId(),
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
                            'master_prono_1' => $matchsItems->getMasterProno1(),
                            'master_prono_n' => $matchsItems->getMasterPronoN(),
                            'master_prono_2' => $matchsItems->getMasterProno2(),
                            'cote_pronostic_1' => $matchsItems->getCot1Pronostic(),
                            'cote_pronostic_n' => $matchsItems->getCoteNPronistic(),
                            'cote_pronostic_2' => $matchsItems->getCote2Pronostic(),
                            'jouer' => true,
                            'noPari' => $this->getPariFroSimple($matchsItems->getId()),
                            'idChampionat' => $matchsItems->getChampionat()->getId(),
                            'nbVote' => $nbVote
                        );
                    }
                    if (!$this->getJouer($matchsItems->getId(), $user->getId())) {

                        $resultMatch[] = array(
                            'idMatch' => $matchsItems->getId(),
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
                            'master_prono_1' => $matchsItems->getMasterProno1(),
                            'master_prono_n' => $matchsItems->getMasterPronoN(),
                            'master_prono_2' => $matchsItems->getMasterProno2(),
                            'cote_pronostic_1' => $matchsItems->getCot1Pronostic(),
                            'cote_pronostic_n' => $matchsItems->getCoteNPronistic(),
                            'cote_pronostic_2' => $matchsItems->getCote2Pronostic(),
                            'jouer' => false,
                            'noPari' => $this->getPariFroSimple($matchsItems->getId()),
                            'idChampionat' => $matchsItems->getChampionat()->getId(),
                        );
                    }
                       /* $resultMatch['list_matchs'][] = array(
                            'idMatch' => $matchsItems->getId(),
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
                            'master_prono_1' => $matchsItems->getMasterProno1(),
                            'master_prono_n' => $matchsItems->getMasterPronoN(),
                            'master_prono_2' => $matchsItems->getMasterProno2(),
                            'cote_pronostic_1' => $matchsItems->getCot1Pronostic(),
                            'cote_pronostic_n' => $matchsItems->getCoteNPronistic(),
                            'cote_pronostic_2' => $matchsItems->getCote2Pronostic(),
                            'jouer' => false,
                            'noPari' => $this->getPariFroSimple($matchsItems->getId()),
                            'idChampionat' => $matchsItems->getChampionat()->getId(),
                        );*/
                 /*   }else{

                    }*/
                }
              /*  if($matchsVote){
                    foreach($matchsVote as $kMatchsVote => $itemsMatchVote){
                     //   var_dump($resultMatch['list_matchs']); die;
                    }
                }*/

                $pub = $this->getObjectRepoFrom(self::ENTITY_PUB, array('isPopup' => false));
                if(is_object($pub) && $pub){
                    $result['banniere'] = 'dplb.arkeup.com/upload/admin/publicite/'.$pub->getCheminPub();
                }

                $result['itemTotal'] = $nbRecapTotal;
                $result['list_matchs'] = $resultMatch;
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "success";
            } else {
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun matchs";
            }

            $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
            $idLast = $lastSolde[0][1];
            $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
            if ($mvtCreditLast) {
                $result['solde'] = $mvtCreditLast->getSoldeCredit();
            }else{
                $result['solde'] = 0;
            }
            return new JsonResponse($result);
        }

    }

    private function noJsonDataCombined()
    {
        //$result['code_erro']
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le json data  doit être spécifié";
        return new JsonResponse($result);
    }
    private function getNbVote($matchId, $userId){
        $dql = " SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN  vu.matchs m
                LEFT JOIN vu.utilisateur u
                WHERE m.id = :matchId AND u.id = :userId AND vu.isCombined = false group by vu.idMise";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
        $query->setParameters(array('matchId' => $matchId, 'userId' => $userId));
        return count($query->getResult());
    }
    private function isMatchsVoted($matchId, $userId){
        $dql = " SELECT vu from ApiDBBundle:VoteUtilisateur vu
                LEFT JOIN  vu.matchs m
                LEFT JOIN vu.utilisateur u
                WHERE m.id = :matchId AND u.id = :userId AND vu.isCombined = false group by vu.idMise";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
        $query->setParameters(array('matchId' => $matchId, 'userId' => $userId));
        $data = $query->getResult();
        if(is_array($data) && count($data) > 0){
            return true;
        }else{
            return false;
        }
    }
    private function getJouer($matchsId, $userId, $date = null, $championnat = null)
    {
        $matchsVote = $this->getRepo(self::ENTITY_MATCHS)->findMatchVote($userId);
        if (!empty($matchsVote)) {

            foreach ($matchsVote as $kMatchsVote => $itemsMatchsVote) {
                //var_dump($itemsMatchsVote->getMatchs()->getId()); die;
                if ($itemsMatchsVote->getMatchs()->getId() == $matchsId) {
                    return true;
                }
            }
        }
        return false;
    }

    private function getDetailsJouer($matchsId, $userId)
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->findDetailsJouer($matchsId, $userId);

        $response = array();
        if ($data) {
            foreach ($data as $kData => $dataItems) {
                /*$response['miseTotal'] = $dataItems->getMisetotale();
                $response['gainPotentiel'] = $dataItems->getGainPotentiel();*/
                $response[] = array(
                    'dateMise' => $dataItems->getDateMise(),
                    'miseTotal' => $dataItems->getMisetotale(),
                    'gainPotentiel' => $dataItems->getGainPotentiel()
                );
            }
            return $response;
        }
        return null;
    }

    private function getGainsPotentiel($idUser, $idMatchs, $idVote)
    {
        $voteUtilisateur = $this->getRepo(self::ENTITY_MATCHS)->findGains($idUser, $idMatchs, $idVote);
        if (!$voteUtilisateur) {
            return null;
        }

        return $voteUtilisateur[0]->getGainPotentiel();
    }

    private function getMiseTotal($idUser, $idMatchs, $idVote)
    {
        $voteUtilisateur = $this->getRepo(self::ENTITY_MATCHS)->findGains($idUser, $idMatchs, $idVote);
        //   var_dump($voteUtilisateur); die;
        if (!$voteUtilisateur) {
            return null;
        }
        return $voteUtilisateur[0]->getMisetotale();
    }

    private function noUser()
    {
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Aucun utilisateur";
        return new JsonResponse($result);
    }

    private function noMatchsId()
    {
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "L' ID du matchs  doit être spécifié";
        return new JsonResponse($result);
    }

    private function noVoteMatchsSimple()
    {

        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le vote matchs simple  doit être spécifié";
        return new JsonResponse($result);
    }

    private function noCombined()
    {
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "isCombined doit être spécifié";
        return new JsonResponse($result);
    }
    private function noDeviceToken(){
        $result = $this->no();
        $result['message'] = "Le token device  doit être sppécifiés";
        return new JsonResponse($result);
    }
    private function noMatchs(){
        $result = $this->no();
        $result['message'] = "Les Matchs  doivent être sppécifiés";
        return new JsonResponse($result);
    }
    private function noMiseTotal(){
        $result = $this->no();
        $result['message'] = "La mise total doit être sppécifié";
        return new JsonResponse($result);
    }
    private function noGain(){
        $result = $this->no();
        $result['message'] = "Le gain doit être sppécifié";
        return new JsonResponse($result);
    }
    private function noToken()
    {
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le token utilisateur doit être spécifié";
        return new JsonResponse($result);
    }


    /**
     * Ws, get nombre de poulet en stock
     * @ApiDoc(
     *  description="Ws, récupérer le nombre de poulet en stock",
     *   parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=false, "description"= " Token de l'utilisateur "},
     *      }
     * )
     */
    public function postGetNbPouletAction(Request $request)
    {
        $token = $request->request->get('token');
        $result = array();
        if (!$token) {
            $result['code_error'] = 2;
            $result['error'] = true;
            $result['success'] = false;
            $result['message'] = "Le parametre token doit être specifie";
            return new JsonResponse($result);
        }
        $currentUser = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if (!$currentUser) {
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucun utilisateur en cours";
            return new JsonResponse($result);
        }
        $credit = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('utilisateur' => $currentUser));

        if (!empty($credit)) {
            $idLast = $credit[0][1];
            $solde = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
            foreach ($solde as $kCredit => $itemsCredit) {
                $result['solde'] = $itemsCredit->getSoldeCredit();
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
        } else {
            $result['solde'] = 0;
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucun Credit pour utilisateur";
            return new JsonResponse($result);
        }
        return new JsonResponse($result);

    }

    /**
     * Ws, Insertion  pari simple
     * @ApiDoc(
     *  description="Ws, Insertion  pari simple",
     *   parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=false, "description"= "Token de l'utilisateur "},
     *          {"name" = "gainPotentiel", "dataType"="string" ,"required"=false, "description"= "Gain potentiel "},
     *          {"name" = "miseTotal", "dataType"="string" ,"required"=false, "description"= "Mise total"},
     *          {"name" = "voteSimple", "dataType"="string" ,"required"=false, "description"= "voteSimple"}
     *      }
     * )
     */
    public function insertPariAction(Request $request)
    {


        $token = $request->request->get('token');
        $gainsPotentiel = $request->request->get('gainPotentiel');
        $miseTotal = $request->request->get('miseTotal');
        $voteMatchsSimple = $request->request->get('voteSimple');
        $matchId = $request->request->get('matchsId');


        if (!$token) {
            return $this->noToken();
        }
        /* if(!$matchId){
            return $this->noMatchsId();
         }*/


        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        $deviceToken = $this->getObjectRepoFrom(self::ENTITY_CONNECTED, array('username' => $user->getEMail()));

        if (!$user) {
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucun utilisateur";
            return new JsonResponse($result);
        }

        if ($voteMatchsSimple === NULL) {
            return $this->noVoteMatchsSimple();
        }
        $matchsId = $request->request->get('matchId');
        if (!$matchsId) {
            return $this->noMatchsId();
        }

        $matchs = $this->getObjectRepoFrom(self::ENTITY_MATCHS, array('id' => $matchsId));

        if ($matchs) {

            //#### TEST DATE
            // RECUPERER L'HEURE ACTUELLE
            $dateTemp = new \DateTime('now');
            // AJOUTER 5 MINUTES
            // SI HEURE ACTUELLE + 5MINUTES > DATEHEURE MATCH -->>BLOQUER
            $date5minAvant = $dateTemp->add(new \DateInterval('PT5M'));
            $dateMatch=$matchs->getDateMatch();
            if($date5minAvant>$dateMatch){
                $result['code_error'] = 0;
                $result['success'] = false;
                $result['error'] = true;
                $result['message'] = "Pari déjà clôturé";
                return new JsonResponse($result);
            }
            //#### TEST DATEgetDateMatch


            $vu = new VoteUtilisateur();
            $vu->setUtilisateur($user);
            $vu->setMisetotale($miseTotal);
            $vu->setGainPotentiel($gainsPotentiel);
            $vu->setVote($voteMatchsSimple);
            $vu->setIsCombined(false);
            //$vu->setIdMise(uniqid(sha1("Mise simple")));
            $vu->setIdMise(time());
            $vu->setDateMise(new \DateTime('now'));
            $vu->setClassement(($gainsPotentiel + $miseTotal) / 2);
          //  var_dump($matchs->getCot1Pronostic()); die;
            $vu->setCote1($matchs->getCot1Pronostic());
            $vu->setCoteN($matchs->getCoteNPronistic());
            $vu->setCote2($matchs->getCote2Pronostic());
            $vu->setMatchs($matchs);

            $this->getEm()->persist($vu);
            $this->getEm()->flush($vu);

            $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
            $idLast = $lastSolde[0][1];
            $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
            if (!$mvtCreditLast) {
                $result['code_error'] = 0;
                $result['success'] = 'Success';
                $result['error'] = 'Error';
                $result['message'] = "Aucun credit";
                return new JsonResponse($result);
            }
            $mvtCredit = new MvtCredit();
            $mvtCredit->setDateMvt(new \DateTime('now'));
            $mvtCredit->setUtilisateur($user);
            $mvtCredit->setVoteUtilisateur($vu);
            $mvtCredit->setSortieCredit($miseTotal);
            //var_dump($mvtCreditLast->getSoldeCredit()); die;
            $mvtCredit->setSoldeCredit($mvtCreditLast->getSoldeCredit() - $miseTotal);
            $mvtCredit->setTypeCredit("JOUER SIMPLE");
            $this->getEm()->persist($mvtCredit);
            $this->getEm()->flush();

            $notifRecap = new NotificationRecapitulation();
            $notifRecap->setUtilisateur($user);
            $notifRecap->setIsNotificationSended(false);
            $notifRecap->setTokenDevice($deviceToken->getTokenSession());
            $notifRecap->setUpdatedAt(new \DateTime('now'));
            $notifRecap->setIsCombined(false);
            $notifRecap->setNbMatchs(1);
            $notifRecap->setMatchs($matchs);
            $this->getEm()->persist($notifRecap);
            $this->getEm()->flush();
            //$matchs->set
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Success";
            // envoie notif
            $device_token = array();
            $messageData = array("message"=> "1","type"=>"concours","categorie"=> "recap");
           // $users = $this->getRepo(self::ENTITY_UTILISATEUR)->findAll();
         //   $users = $this->getRepoFrom(self::ENTITY_UTILISATEUR, array('id' => $user->getId()));
           // var_dump($user); die;

            $users = $this->getObjectRepoFrom(self::ENTITY_CONNECTED, array('username' => $user->getEmail()));

            array_push($device_token, $users->getDevice());
            /*var_dump($device_token); die;
            foreach($users as $user){
                $devices = $user->getDevices();
                foreach ($devices as $device){
                    //$device_token[] = $device->getToken();
                    array_push($device_token, $device->getToken());
                }
            }*/

            $data = array(
                'registration_ids' => $device_token,
                'data' => $messageData
            );
            $this->sendGCMNotification($data);

        } else {
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucun matchs trouvé";
        }
        return new JsonResponse($result);
    }

    /**
     * Ws, Insertion  combiné
     * @ApiDoc(
     *  description="Ws, Insertion  pari combiné",
     *   parameters = {
     *          {"name" = "jsonDataCombined", "dataType"="string" ,"required"=false, "description"= " Json data pour pari combine"},
     *      }
     * )
     */
    public function insertPariCombinedAction(Request $request)
    {

        $jsonDataCombined = $request->getContent();
        if (!$jsonDataCombined) {
            return $this->noJsonDataCombined();
        }

        $data = json_decode($jsonDataCombined, true);
        if(array_key_exists('token', $data)){
            $token = $data['token'];
        }else{
            return $this->noToken();
        }
        if(array_key_exists('gainPotentiel', $data)){
            $gainsPotentiel = $data['gainPotentiel'];
        }else{
            return $this->noGain();
        }
        if(array_key_exists('miseTotal', $data)){
            $miseTotal = $data['miseTotal'];
        }else{
            return $this->noMiseTotal();
        }
        if(array_key_exists('matchs', $data)){
            $matchs = $data['matchs'];
        }else{
            return $this->noMatchs();
        }
        $deviceToken = null;
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        $deviceToken = $this->getObjectRepoFrom(self::ENTITY_CONNECTED, array('username' => $user->getEMail()))->getDevice();

        if (!empty($matchs)) {
            //$idMise = uniqid(sha1("mise double"));
           $idMise = time();
            $count = 0;
            foreach ($matchs as $kMatchs => $itemsMatchs) {
                $count = $count + 1;
                $idMatchs = $itemsMatchs['id'];
                $voteMatchs = $itemsMatchs['vote'];
                $matchs = $this->getObjectRepoFrom(self::ENTITY_MATCHS, array('id' => $idMatchs));
                if (!$matchs) {
                    //                die('pas de matchs');
                }

                $vu = new VoteUtilisateur();
                $vu->setVote($voteMatchs);
                $vu->setMatchs($matchs);
                $vu->setUtilisateur($user);
                $vu->setGainPotentiel($gainsPotentiel);
                $vu->setMisetotale($miseTotal);
                $vu->setIsCombined(true);
                $vu->setIdMise($idMise);
                $vu->setClassement($gainsPotentiel + $miseTotal / 2);
                $vu->setCote1($matchs->getCot1Pronostic());
                $vu->setCoteN($matchs->getCoteNPronistic());
                $vu->setCote2($matchs->getCote2Pronostic());

                $vu->setDateMise(new \DateTime('now'));
                $this->getEm()->persist($vu);
                $this->getEm()->flush();
                // Todo: a revoir


            }
            $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
            $idLast = $lastSolde[0][1];
            $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
            if (!$mvtCreditLast) {
                die('pas de mvt credit last');
            }

            $mvtCredit = new MvtCredit();
            $mvtCredit->setUtilisateur($user);
            $mvtCredit->setVoteUtilisateur($vu);
            $mvtCredit->setSortieCredit($miseTotal);
            $mvtCredit->setSoldeCredit($mvtCreditLast->getSoldeCredit() - $miseTotal);
            $mvtCredit->setDateMvt(new \DateTime('now'));
            $mvtCredit->setTypeCredit('JOUER COMBINE');
            $this->getEm()->persist($mvtCredit);
            $this->getEm()->flush();


            if(!$deviceToken){

            }
            $notifRecap = new NotificationRecapitulation();
            $notifRecap->setUtilisateur($user);
            $notifRecap->setIsNotificationSended(false);
            $notifRecap->setTokenDevice($deviceToken);
            $notifRecap->setUpdatedAt(new \DateTime('now'));
            $notifRecap->setIsCombined(true);
            $notifRecap->setMatchs($matchs);
            $notifRecap->setNbMatchs($count);
            $notifRecap->setTokenDevice($deviceToken);
            $this->getEm()->persist($notifRecap);
            $this->getEm()->flush();

            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Success";
            //user
            $device_token = array();
            $messageData = array("message"=> "1","type"=>"concours","categorie"=> "recap");
            $users = $this->getObjectRepoFrom(self::ENTITY_CONNECTED, array('username' => $user->getEmail()));
            array_push($device_token, $users->getDevice());
            $data = array(
                'registration_ids' => $device_token,
                'data' => $messageData
            );
            $this->sendGCMNotification($data);

            return new JsonResponse($result);
        }
    }

    public function getPariFroSimple($matchsId){

        $match = $this->getObjectRepoFrom(self::ENTITY_MATCHS, array('id' => $matchsId));
        if(!$match or is_null($match)){
            return false;
        }
        if($match->getIsNoPari() === true){
            return true;
        }else{
            return false;
        }
    }
    private function noConcour(){
        $result['code_error'] = 0;
        $result['error']= false;
        $result['success'] = true;
        $result['message'] = "Aucun concours en cours";
        return new JsonResponse($result);
    }

}