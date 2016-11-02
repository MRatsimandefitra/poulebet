<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends ApiController implements InterfaceDB
{
    public function postGetAccountProfilAction(Request $request){
        $token = $request->get('token');

        if(!$token){
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->noUser();
        }

        $result = array();
        if($user && is_object($user)){
            $result['account'] = array(
                'photo'=> 'http://dplb.arkeup.com/upload/utilisateur/'. $user->getCheminPhoto(),

            );
            if($user->getUsername()){
                $result['account']['nomAffiche'] = $user->getUsername();
            }elseif($user->getPrenom()){
                $result['account']['nomAffiche'] = $user->getPrenom();
            }
        }
        //nbPoulet
        $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
        $idLast = $lastSolde[0][1];
        $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
        if ($mvtCreditLast) {
            $result['solde'] = $mvtCreditLast->getSoldeCredit();
        }else{
            $result['solde'] = 0;
        }
        //mise
        $this->getRepo(self::ENTITY_MATCHS)->findTotalGainsOfUser($user->getId());
        // gains
        $gains = $this->getRepo(self::ENTITY_MATCHS)->findTotalGainsOfUser($user->getId());
        if(is_array($gains) && count($gains) > 0){
            $totalGains = 0;
            foreach($gains as $kGains => $itemsGains){
                $totalMise = $totalGains + $itemsGains->getMiseTotale();
            }
            $result['totalMiseTotal'] = $totalMise;

        }else{
            $result['totalMiseTotal'] = null;
        }
        if(is_array($gains) && count($gains) > 0){
            $totalGains = 0;
            foreach($gains as $kGains => $itemsGains){
                $totalGains = $totalGains + $itemsGains->getGainPotentiel();
            }
            $result['totalGain'] = $totalGains;

        }else{
            $result['totalGain'] = null;
        }
        $totalEncours = $this->getRepo(self::ENTITY_MATCHS)->findTotalMatchsEnCours($user->getId());

        if(is_array($totalEncours) && count($totalEncours) > 0){
            ;
            $totalEnCour  = 0;
            foreach($totalEncours as $itemsTotalEnCours){
                $totalEnCour = $totalEnCour + $itemsTotalEnCours->getMiseTotale();
            }
            $result['totalEnCours'] =$totalEnCour;
        }else{
            $result['totalEnCours'] = null;
        }
         //   var_dump($result); die;
        // championat
        $championat = $this->getRepo(self::ENTITY_MATCHS)->findRecapitulationForUserForChampionat($user->getId());
        if(is_array($championat) && count($championat) > 0){
            foreach($championat as $kChampionat => $itemsChampionat){
                $result['list_championat'][] = array(
                    'idChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getId(),
                    'nomChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $itemsChampionat->getMatchs()->getChampionat()->getFullNameChampionat(),
                );
            }
        }
        // 2 dernier concours
        $concours = $this->getRepo(self::ENTITY_MATCHS)->findTwoLastConcour();
        //recapitulation par utilisateur
        $recapitulation = $this->getRepo(self::ENTITY_MATCHS)->findRecapitulationForUser($user->getId());


        if(is_array($recapitulation)&& count($recapitulation) > 0){
            foreach($recapitulation as $kRecapitulation => $itemsRecapitulation){
                if($itemsRecapitulation->getIsCombined() === false) {
                    $result['list_match'][] = array(
                        'idMatch' => $itemsRecapitulation->getMatchs()->getId(),
                        'dateMatch' => $itemsRecapitulation->getMatchs()->getDateMatch(),
                        'equipeDomicile' => $itemsRecapitulation->getMatchs()->getEquipeDomicile(),
                        'equipeVisiteur' => $itemsRecapitulation->getMatchs()->getEquipeVisiteur(),
                        'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsRecapitulation->getMatchs()->getCheminLogoDomicile() . '.png',// $itemsRecapitulationData->getTeamsDomicile()->getLogo(),
                        'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsRecapitulation->getMatchs()->getCheminLogoVisiteur() . '.png',// $itemsRecapitulationData->getTeamsVisiteur()->getLogo(),
                        'score' => $itemsRecapitulation->getMatchs()->getScore(),
                        'scoreDomicile' => substr($itemsRecapitulation->getMatchs()->getScore(), 0, 1),
                        'scoreVisiteur' => substr($itemsRecapitulation->getMatchs()->getScore(), -1, 1),
                        'status' => $itemsRecapitulation->getMatchs()->getStatusMatch(),
                        'tempsEcoules' => $itemsRecapitulation->getMatchs()->getTempsEcoules(),
                        'live' => ($itemsRecapitulation->getMatchs()->getStatusMatch() == 'active') ? true : false,
                        'master_prono_1' => $itemsRecapitulation->getMatchs()->getMasterProno1(),
                        'master_prono_n' => $itemsRecapitulation->getMatchs()->getMasterPronoN(),
                        'master_prono_2' => $itemsRecapitulation->getMatchs()->getMasterProno2(),
                        'cote_pronostic_1' => $itemsRecapitulation->getCote1(),
                        'cote_pronostic_n' => $itemsRecapitulation->getCoteN(),
                        'cote_pronostic_2' => $itemsRecapitulation->getCote2(),
                        'voted_equipe' => $itemsRecapitulation->getVote(),
                        'isCombined' => $itemsRecapitulation->getIsCombined(),
                        'gainsPotentiel' => $itemsRecapitulation->getGainPotentiel(),
                        'miseTotale' => $itemsRecapitulation->getMiseTotale(),
                        'idChampionat' => $itemsRecapitulation->getMatchs()->getChampionat()->getId()
                    );

                }else{

                    if ($this->getStatusRecap($itemsRecapitulation->getId(), $itemsRecapitulation->getIdMise(), $itemsRecapitulation->getDateMise()) === false) {
                        $dataIsGagne = false;
                    }
                    if ($itemsRecapitulation->getMatchs()->getStatusMatch() != 'finished') {
                        $dataStatus = 'En cours';
                    } elseif ($dataIsGagne === true) {
                        $dataStatus = "Gagné";
                    } else {
                        $dataStatus = "Terminé";
                    }
                    $result['list_match'][] = array(
                        'miseId' =>$itemsRecapitulation->getIdMise(),
                        'gainsPotentiel' => $itemsRecapitulation->getGainPotentiel(),
                        'miseTotal' => $itemsRecapitulation->getMiseTotale(),
                        'matchs' => array(
                            'idMatch' => $itemsRecapitulation->getMatchs()->getId(),
                            'dateMatch' => $itemsRecapitulation->getMatchs()->getDateMatch(),
                            'equipeDomicile' => $itemsRecapitulation->getMatchs()->getEquipeDomicile(),
                            'equipeVisiteur' => $itemsRecapitulation->getMatchs()->getEquipeVisiteur(),
                            'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsRecapitulation->getMatchs()->getCheminLogoDomicile() . '.png',// $itemsRecapitulationData->getTeamsDomicile()->getLogo(),
                            'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsRecapitulation->getMatchs()->getCheminLogoVisiteur() . '.png',// $itemsRecapitulationData->getTeamsVisiteur()->getLogo(),
                            'score' => $itemsRecapitulation->getMatchs()->getScore(),
                            'scoreDomicile' => substr($itemsRecapitulation->getMatchs()->getScore(), 0, 1),
                            'scoreVisiteur' => substr($itemsRecapitulation->getMatchs()->getScore(), -1, 1),
                            'status' => $itemsRecapitulation->getMatchs()->getStatusMatch(),
                            'tempsEcoules' => $itemsRecapitulation->getMatchs()->getTempsEcoules(),
                            'live' => ($itemsRecapitulation->getMatchs()->getStatusMatch() == 'active') ? true : false,
                            'master_prono_1' => $itemsRecapitulation->getMatchs()->getMasterProno1(),
                            'master_prono_n' => $itemsRecapitulation->getMatchs()->getMasterPronoN(),
                            'master_prono_2' => $itemsRecapitulation->getMatchs()->getMasterProno2(),
                            'cote_pronostic_1' => $itemsRecapitulation->getCote1(),
                            'cote_pronostic_n' => $itemsRecapitulation->getCoteN(),
                            'cote_pronostic_2' => $itemsRecapitulation->getCote2(),
                            'voted_equipe' => $itemsRecapitulation->getVote(),
                          //  'isGagne' => $this->getStatusRecap($itemsRecapitulation->getId())
                        ),
                        'gagnantCombine' => $dataIsGagne,
                        'statusCombine' => $dataStatus

                    );
                }

                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;
                $result['message'] = "Success";
            }

        }else{
            $result['code_error']= 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucune récapitulatio trouvée";
        }
        return new JsonResponse($result);
    }

    public function postGetUploadPhotoAction(Request $request){
        $binaryPhoto = $request->get('image');
        if(!$binaryPhoto){
            return $this->noImage();
        }

            $baseUrl = get_site_url() . '/' ;
            $date_now = new \DateTime( 'now' ) ;
            $timestamp = $date_now->format('Ymdhis') ;
            $filename = 'wp-content/uploads/image_'. $timestamp . '.png' ;
            $binary=base64_decode($binaryPhoto);
            $file = fopen($filename, 'wb');
            fwrite($file, $binary);
            fclose($file);
            return $baseUrl.$filename ;
    }

    //private function no
    private function noUser(){
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] ="Le token doit être spécifié";
        return new JsonResponse($result);
    }

    private function noToken(){
        $result['code_error'] = 4;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] ="Le token doit être spécifié";
        return new JsonResponse($result);
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
}
