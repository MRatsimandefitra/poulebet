<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\CommonBundle\Fixed\InterfacePari;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class PariController extends ApiController implements InterfaceDB
{
    public function postGetAllMathsAction(Request $request){
        $date = $request->request->get('date');
        $championat = $request->request->get('championat');
        $token = $request->request->get('token');
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championat);
        $championat = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForPari($date, $championat, true);

        $result = array();

        if($championat){
            foreach($championat as $kChampionat => $itemsChampionat){

                $result['list_championat'][] = array(
                    'idChampionat' => $itemsChampionat->getChampionat()->getId(),
                    'nomChampionat' => $itemsChampionat->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $itemsChampionat->getChampionat()->getFullNameChampionat()
                );
            }
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
                    'gainsPotentiel' => $this->getGainsPotentiel($user->getId(), $matchsItems->getId()),
                    'miseTotal' => $this->getMiseTotal($user->getId(), $matchsItems->getId()),
                    'idChampionat' => $matchsItems->getChampionat()->getId()
                );
            }
        }



        return new JsonResponse($result);
    }

    private function getGainsPotentiel($idUser, $idMatchs){
        $voteUtilisateur = $this->getRepo(self::ENTITY_MATCHS)->findGains($idUser, $idMatchs);
        if(!$voteUtilisateur){
            return null;
        }
        return $voteUtilisateur->getGainPotentiel();
    }
    private function getMiseTotal($idUser, $idMatchs){
        $voteUtilisateur = $this->getRepo(self::ENTITY_MATCHS)->findGains($idUser, $idMatchs);
        if(!$voteUtilisateur){
            return null;
        }
        return $voteUtilisateur->getMiseTotal();
    }
}
