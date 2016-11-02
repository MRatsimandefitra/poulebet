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
    public function postGetListRecapAction(Request $request)
    {

        $isCombined = (bool)$request->request->get('isCombined');
        if ($isCombined === NULL) {
            return $this->noCombined();
        }
        $token = $request->request->get('token');
        if ($token === NULL) {
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if (!$user) {
            return $this->noUser();
        }
        $page = $request->request->get('page');
        if (!$page) {
            $page = 1;
        }
        // var_dump($page); die;
        $this->getStateCombined($user->getId());
        $nbRecapTotal = $this->getRepo(self::ENTITY_MATCHS)->findNbRecapMatchsSimpleAndCombined($user->getId());
        $result = array();
        if ($isCombined) {
            $nbRecap = $this->getRepo(self::ENTITY_MATCHS)->findNbMatchsForRecapCombined($user->getId());
            //var_dump($nbRecap); die;
            if (!empty($nbRecap)) {
                $count = 0;
                $championat = $this->getRepo(self::ENTITY_MATCHS)->findChampionatForRecapCombined($user->getId());
                if (!empty($championat)) {
                    foreach ($championat as $kChampionat => $itemsChampionat) {

                        $result['list_championat'][] = array(
                            'idChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getId(),
                            'nomChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                            'fullNameChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getFullNameChampionat(),
                        );
                    }
                }


                foreach ($nbRecap as $itemsNbRecap) {
                    $count = $count + 1;
                    $idMise[] = $itemsNbRecap->getIdMise();
                }
                // pagination
                $totalItems = count($idMise);
                $perPage = 10;
                $nbPage = ceil($totalItems / $perPage);
                $pageNow = $page;
                if ($pageNow >= $nbPage) {
                    $pageNow = $nbPage;
                }
                $countTotalRow = $page * $perPage;

                //$countRow = 0;
                $count = 0;
                $countRow = $countTotalRow - $perPage;

                foreach ($idMise as $k => $itemsIdMise) {
                    $count = $count + 1;
                    if ($countRow == $k) {
                        $matchs = array();
                        $countRow= $countRow + 1;
                        $ss = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForRecapCombined($user->getId(), $itemsIdMise);

                        if ($ss) {
                            $dataIsGagne = true;
                            $dataStatus = null;
                            foreach ($ss as $k => $v) {

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
                                    'cote_pronostic_1' => $v->getCote1(),
                                    'cote_pronostic_n' => $v->getCoteN(),
                                    'cote_pronostic_2' => $v->getCote2(),
                                    'voted_equipe' => $v->getVote(),
                                    'isGagne' => $this->getStatusRecap($v->getId())
                                );
                                if ($this->getStatusRecap($v->getId(), $v->getIdMise(), $v->getDateMise()) === false) {
                                    $dataIsGagne = false;
                                }
                                if ($v->getMatchs()->getStatusMatch() != 'finished') {
                                    $dataStatus = 'En cours';
                                } elseif ($dataIsGagne === true) {
                                    $dataStatus = "Gagné";
                                } else {
                                    $dataStatus = "Terminé";
                                }
                            }
                        }

                        $result['list_mise'][] = array(
                            'miseId' => $itemsIdMise,
                            'gainsPotentiel' => $gain,
                            'miseTotal' => $miseTotal,
                            'matchs' => $matchs,
                            'gagnantCombine' => $dataIsGagne,
                            'statusCombine' => $dataStatus
                        );
                        $banniere = $this->getObjectRepoFrom(self::ENTITY_PUB, array('isPopup' => false ));
                        if($banniere && is_object($banniere)){
                                $result['banniere'] = 'http://dplb.arkeup.com/upload/publicite/'.$banniere->getCheminPub();
                        }else{
                            $result['banniere'] = null;
                        }
                        $result['pagination']['total'] = $totalItems;
                        $result['pagination']['perPage'] = $perPage;
                        $result['pagination']['pageNow'] = $pageNow;
                        $result['pagination']['nbPage'] = $nbPage;
                        $result['itemTotal'] = $nbRecapTotal;
                        $resultMatchs[$itemsIdMise]['gagnant'] = "";
                    }
                }

                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "success";
                return new JsonResponse($result);
            } else {
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun resultat trouve";
                return new JsonResponse($result);
            }
        } else {
            // championat
            $championat = $this->getRepo(self::ENTITY_MATCHS)->findChampionatVoteSimple($user->getId());
            #$championat = $this->getRepo(self::ENTITY_MATCHS)->findChampionatVoteSimpleDQL($user->getId());
            #$championat = $this->paginate($page, $championat);

            if ($championat) {

                foreach ($championat as $kChampionat => $itemsChampionat) {
                    $result['list_championat'][] = array(
                        'idChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getId(),
                        'nomChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                        'fullNameChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                    );
                }
            }
            // matchs
            $nbRecap = $this->getRepo(self::ENTITY_MATCHS)->findNbMatchsVoteSimple($user->getId());

            // pagination
            $totalItems = count($nbRecap);

            $perPage = 10;
            $nbPage = ceil($totalItems / $perPage);
            $pageNow = $page;
            if ($pageNow >= $nbPage) {
                $pageNow = $nbPage;
            }

            $countBoucle = $page * $perPage;

            if (!empty($nbRecap)) {
                $count = $countBoucle - $perPage;

                foreach ($nbRecap as $k => $vItems) {

                    if ($count == $k && $count < $countBoucle ) {
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
                            'cote_pronostic_1' => $vItems->getCote1(),
                            'cote_pronostic_n' => $vItems->getCoteN(),
                            'cote_pronostic_2' => $vItems->getCote2(),
                            'gainsPotentiel' => $vItems->getGainPotentiel(),
                            'miseTotal' => $vItems->getMisetotale(),
                            'state' => $this->getMatchsState($vItems->getId()),
                            'idChampionat' => $vItems->getMatchs()->getChampionat()->getId(),
                            'voted_equipe' => $vItems->getVote(),
                            'isGagne' => $this->getIsGagne($vItems->getId(), $vItems->getIdMise(), $vItems->getDateMise())
                        );
                        $count = $count + 1;
                    }

                }
              //  $result['nb'] = count($nbRecap);
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Success";

                $result['pagination']['total'] = $totalItems;
                $result['pagination']['perPage'] = $perPage;
                $result['pagination']['pageNow'] = $pageNow;
                $result['pagination']['nbPage'] = $nbPage;

                $banniere = $this->getObjectRepoFrom(self::ENTITY_PUB, array('isPopup' => false ));
                if($banniere && is_object($banniere)){
                    $result['banniere'] = 'http://dplb.arkeup.com/upload/publicite/'.$banniere->getCheminPub();
                }else{
                    $result['banniere'] = null;
                }

                $result['itemTotal'] = $nbRecapTotal;

                return new JsonResponse($result);

            } else {

                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Aucun donne disponible";
                return new JsonResponse($result);
            }


        }

    }


    private function noToken()
    {
        $result = $this->no();
        $result['message'] = " Token doit être specifie";
        return new JsonResponse($result);
    }

    private function noCombined()
    {
        $result = $this->no();
        $result['message'] = " IsCombined doit être specifie";
        return new JsonResponse($result);
    }

    private function no()
    {
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        return $result;
    }

    private function noUser()
    {
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Aucun utilisateur compatible avec le token";
        return new JsonResponse($result);
    }

    private function getMatchsState($idMatch)
    {
        $matchs = $this->getObjectRepoFrom(self::ENTITY_MATCHS, array('id' => $idMatch));
        if ($matchs) {
            $state = $matchs->getStatusMatchs();
            return $state;
        }
        return null;
    }

    private function getStateCombined($utilisateur)
    {
        $matchsVote = $this->getRepo(self::ENTITY_MATCHS)->findStateForCombined($utilisateur);

        if ($matchsVote) {
            foreach ($matchsVote as $kMatchsVote => $itemsMatchsVote) {
                //var_dump($itemsMatchsVote->getMatchs()->getId()); die;
                $matchs = $this->getRepoFrom(self::ENTITY_MATCHS, array('id' => $itemsMatchsVote->getMatchs()->getId()));
                if (!empty($matchs)) {
                    //foreach($matchs as $kmatchs)
                }
            }
        }
    }

    private function getStatusRecap($idVoteUtilisateur)
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->findStatusRecap($idVoteUtilisateur);
        if (is_array($data) && count($data) > 0) {
            foreach ($data as $kData => $itemsData) {
                $gagnant = $itemsData->getGagnant();
                $vote = $itemsData->getVote();
                if ($gagnant == $vote) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    private function getIsGagne($idVote, $idMise, $date)
    {
        $matchs = $this->getRepo(self::ENTITY_MATCHS)->findRecapMatchGagnant($idVote, $idMise, $date);
        if (is_array($matchs) && count($matchs) > 0) {
            foreach ($matchs as $kMatchs => $itemsMatchs) {
                $statusMatchs = $itemsMatchs->getMatchs()->getStatusMatchs();
                if ($statusMatchs === 'finished') {
                    $gagnant = $itemsMatchs->getGagnat();
                    if ($gagnant === 1) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    private function paginate($page, $query, $perPage = 10)
    {
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $page /*page number*/,
            $perPage
        );
        return $pagination;
    }
}

