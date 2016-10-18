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

        $date = $request->request->get('date');
        $championatWs = $request->request->get('championat');
        $token = $request->request->get('token');
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
                        'idChampionat' => $matchsItems->getChampionat()->getId()
                    );
                }
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
            $concourEncour = $this->getRepo(self::ENTITY_MATCHS)->findIdConcourByDate();
            if(!$concourEncour){
                //die('no Conour');
            }
            $idConcour = $concourEncour[0]->getId();
            $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, null, $idConcour);

            // $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPariNoJouer($date, $championatWs, null, $user->getId(), $matchs->getId());

            $matchsVote = $this->getRepo(self::ENTITY_MATCHS)->findMatchVote($date, $championatWs);
            $championat = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championatWs, true, $idConcour);
            $resultTmp = array();
            //championat
            if ($championat) {
                foreach ($championat as $kChampionat => $itemsChampionat) {

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
            } else {
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun championat";
                return new JsonResponse($result);
            }

            //
            if ($matchsVote) {
                foreach ($matchsVote as $kMatchsVote => $itemsMatchVote) {
                    $resultTmp['list_matchs'][] = array(
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
                        'cote_pronostic_1' => $itemsMatchVote->getMatchs()->getCot1Pronostic(),
                        'cote_pronostic_n' => $itemsMatchVote->getMatchs()->getCoteNPronistic(),
                        'cote_pronostic_2' => $itemsMatchVote->getMatchs()->getCote2Pronostic(),
                        'gainsPotentiel' => $this->getGainsPotentiel($user->getId(), $itemsMatchVote->getMatchs()->getId(), $itemsMatchVote->getId()),
                        'miseTotal' => $this->getMiseTotal($user->getId(), $itemsMatchVote->getMatchs()->getId(), $itemsMatchVote->getId()),
                        'jouer' => true,
                        'idChampionat' => $itemsMatchVote->getMatchs()->getChampionat()->getId()
                    );

                }
            }

            if ($matchs) {

                foreach ($matchs as $kMatchs => $matchsItems) {
                    if (!$this->getJouer($matchsItems->getId())) {
                        $resultTmp['list_matchs'][] = array(
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
                            'idChampionat' => $matchsItems->getChampionat()->getId()
                        );
                    }


                }

                foreach($resultTmp['list_matchs'] as $itemsListMatch){
                    $dateMatch[] = $itemsListMatch['dateMatch'];
                }
                ksort($dateMatch);
                foreach($dateMatch as $kDateMatch => $itemsDateMatch){
                    $matchsQuery = $this->getObjectRepoFrom(self::ENTITY_MATCHS, array(
                        'dateMatch' => $itemsDateMatch
                    ));
                    $result["list_matchs"][] = array(
                        'idMatch' => $matchsQuery->getId(),
                        'dateMatch' => $matchsQuery->getDateMatch(),
                        'equipeDomicile' => $matchsQuery->getEquipeDomicile(),
                        'equipeVisiteur' => $matchsQuery->getEquipeVisiteur(),
                        'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $matchsQuery->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                        'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $matchsQuery->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                        'score' => $matchsQuery->getScore(),
                        'scoreDomicile' => substr($matchsQuery->getScore(), 0, 1),
                        'scoreVisiteur' => substr($matchsQuery->getScore(), -1, 1),
                        'status' => $matchsQuery->getStatusMatch(),
                        'tempsEcoules' => $matchsQuery->getTempsEcoules(),
                        'live' => ($matchsQuery->getStatusMatch() == 'active') ? true : false,
                        'master_prono_1' => $matchsQuery->getMasterProno1(),
                        'master_prono_n' => $matchsQuery->getMasterPronoN(),
                        'master_prono_2' => $matchsQuery->getMasterProno2(),
                        'cote_pronostic_1' => $matchsQuery->getCot1Pronostic(),
                        'cote_pronostic_n' => $matchsQuery->getCoteNPronistic(),
                        'cote_pronostic_2' => $matchsQuery->getCote2Pronostic(),
                        'jouer' => false,
                        'idChampionat' => $matchsQuery->getChampionat()->getId()
                    );
                }
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

    private function getJouer($matchsId)
    {
        $matchsVote = $this->getRepo(self::ENTITY_MATCHS)->findMatchVote();
        if ($matchsVote) {

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
        //$matchId = $request->request->get('matchsId');


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

            $vu = new VoteUtilisateur();
            $vu->setUtilisateur($user);
            $vu->setMisetotale($miseTotal);
            $vu->setGainPotentiel($gainsPotentiel);
            $vu->setVote($voteMatchsSimple);
            $vu->setIsCombined(false);
            $vu->setIdMise(uniqid(sha1("Mise simple")));
            $vu->setDateMise(new \DateTime('now'));
            $vu->setClassement($gainsPotentiel + $miseTotal / 2);
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
                die('pas de mvt credit last');
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
        /*if(array_key_exists('deviseToken', $data)){
            $deviceToken = $data['deviceToken'];
        }else{
            return $this->noDeviceToken();
        }*/
        $deviceToken = null;
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        $deviceToken = $this->getObjectRepoFrom(self::ENTITY_CONNECTED, array('username' => $user->getEMail()))->getDevice();

        if (!empty($matchs)) {
            $idMise = uniqid(sha1("mise double"));
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
            $mvtCredit->setSortieCredit($gainsPotentiel);
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
            return new JsonResponse($result);
        }
    }
}