<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class LiveScoreController extends ApiController implements InterfaceDB
{


    /**
     * @ApiDoc(
     *      description = "Recuperer les match en live "
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getMatchLiveScoreOldAction(Request $request)
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->getMatchLiveScore();
        $result = array();
        if ($data) {
            $ancienNameChampionat = "";
            $dataDetails= array();
            $dataDetailsFull = array();
            foreach ($data as $k => $vData) {

                if ($ancienNameChampionat != $vData->getChampionat()->getNomChampionat()) {
                    $championat[] = $vData->getChampionat()->getNomChampionat();
                }
                $ancienNameChampionat = $vData->getChampionat()->getNomChampionat();
            }

            foreach ($championat as $kChampionat => $vChampionat) {
                $dql = "SELECT m from ApiDBBundle:Matchs m
                        LEFT JOIN m.championat ch
                        WHERE ch.nomChampionat LIKE :vchampionat
                        AND m.statusMatch LIKE 'active' ";
                $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
                $query->setParameter('vchampionat', $vChampionat);
                $dataMatchs = $query->getResult();

                foreach ($dataMatchs as $kDataMatchs => $vDataMatchs) {
                    //var_dump($vDataMatchs->getChampionat()->getId()); //break;
                   // var_dump($dataDetailsFull['group']);
             /*       if(sizeof($dataDetailsFull['group']) > 0 ){*/

                        /*foreach($dataDetailsFull['group'] as $groupItems){
                            var_dump($groupItems); die;
                            if(array_key_exists('id', $groupItems) ){*/
                             /*   if(array_key_exists('group', $dataDetailsFull) && !in_array($vDataMatchs->getChampionat()->getId(), $dataDetailsFull['group'])) {*/
                /*                    $dataDetails[] = array(
                                        'id' => $vDataMatchs->getChampionat()->getId(),
                                        'nom' => $vDataMatchs->getChampionat()->getFullNameChampionat()
                                    );
                */                /*}*/
                            /*}
                        }*/
                    /*}*/
                    /*if(!in_array($vDataMatchs->getChampionat()->getId(), $dataDetailsFull['group'])) {
                        $dataDetailsFull['group'][] = array(
                            'id' => $vDataMatchs->getChampionat()->getId(),
                            'nom' => $vDataMatchs->getChampionat()->getFullNameChampionat()
                        );
                    }*/

                    $dataDetailsFull['matchs'][] = array(
                        'teamsDomicile' => $vDataMatchs->getTeamsDomicile()->getFullNameClub(),
                        'teamsVisiteur' => $vDataMatchs->getTeamsVisiteur()->getFullNameClub(),
                        'score' => $vDataMatchs->getScore(),
                        'scoreDomicile' => substr($vDataMatchs->getScore(), 0, 1),
                        'scoreVisiteur' => substr($vDataMatchs->getScore(), -1, 1),
                        'live' => ($vData->getStatusMatch() == 'active') ? true : false,
                        'current-state' => array(
                            'period' => $vDataMatchs->getPeriod(),
                            'minute' => $vDataMatchs->getMinute()
                        ),
                        'championat' => $vData->getChampionat()->getId()

                    );
                    /*if($vData->getStatusMatch() == 'active'){
                        $dataDetails['matchs'][]['current-state'] = array(
                                'period' => $vData->getPeriod(),
                                'minute' => $vData->getMinute()
                        );
                    }*/
                }

/*                foreach($dataDetails['group'] as $k => $v){
                    if(!in_array($v['id'],$dataDetailsFull['group'][$k])){
                        $dataDetailsFull['group'][] = array(
                            'id' => $vDataMatchs->getChampionat()->getId(),
                            'nom' => $vDataMatchs->getChampionat()->getFullNameChampionat()
                        );
                    }
                }*/

            }

            $result = $dataDetailsFull;
            $result['code_error'] = 4;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucun matchs n'a été trouvé";
        } else {
            $result['code_error'] = 0;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucun matchs n'a été trouvé";
        }

        return new JsonResponse($result);
    }


    /**
     * @ApiDoc(
     *      description = "Recuperer les match en live "
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getMatchLiveScoreAction(Request $request){

        $dqlChampionat = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                where m.dateMatch BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), 7, 'day' )
                AND m.statusMatch LIKE 'active' GROUP BY ch.fullNameChampionat ORDER BY ch.rang ASC, m.dateMatch ASC";
        $queryChampionat = $this->get('doctrine.orm.entity_manager')->createQuery($dqlChampionat);
        $matchLiveChampionat = $queryChampionat->getResult();
        $championat = array();
        foreach($matchLiveChampionat as $kLiveChampionat => $vLiveChampionatItems){

            $championat[] = array(
                'id' => $vLiveChampionatItems->getChampionat()->getId(),
                'nom' => $vLiveChampionatItems->getChampionat()->getFullNameChampionat()
            );
        }
        $dataDetailsFull['group'] = $championat;
        $dqlMatchs = "SELECT m from ApiDBBundle:Matchs m
                LEFT JOIN m.championat ch
                where m.dateMatch BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), 7, 'day' )
                AND m.statusMatch LIKE 'active' ORDER BY ch.rang ASC, m.dateMatch ASC";
        $queryMatchs = $this->get('doctrine.orm.entity_manager')->createQuery($dqlMatchs);
        $matchLive = $queryMatchs->getResult();

        foreach($matchLive as $k => $matchLiveItems){
            $dataDetailsFull['matchs'][] = array(
                'teamsDomicile' => $matchLiveItems->getTeamsDomicile()->getFullNameClub(),
                'teamsVisiteur' => $matchLiveItems->getTeamsVisiteur()->getFullNameClub(),
                'score' => $matchLiveItems->getScore(),
                'scoreDomicile' => substr($matchLiveItems->getScore(), 0, 1),
                'scoreVisiteur' => substr($matchLiveItems->getScore(), -1, 1),
                'live' => ($matchLiveItems->getStatusMatch() == 'active') ? true : false,
                'current-state' => array(
                    'period' => $matchLiveItems->getPeriod(),
                    'minute' => $matchLiveItems->getMinute()
                ),
                'championat' => $matchLiveItems->getChampionat()->getId(),
                'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $matchLiveItems->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $matchLiveItems->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
            );
        }
        $result = $dataDetailsFull;
        if(!empty($result)){
            $result = $dataDetailsFull;
            $result['code_error'] = 4;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucun matchs n'a été trouvé";
        }else{
            $result['code_error'] = 0;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucun matchs n'a été trouvé";
        }
        return new JsonResponse($result);


    }


    /**
     * @ApiDoc(
     *      description = "Récuperer matchs de la jpourné "
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getMatchLiveScoreTodayAction(Request $request){
        $matchsToday = $this->getRepo(self::ENTITY_MATCHS)->findMatchsToday();
        $championatToday = $this->getRepo(self::ENTITY_MATCHS)->findChampionatToday();
        $result = array();

        if(is_array($championatToday) && count($championatToday) > 0 ){
            foreach($championatToday as $kChampionatToday => $itemsChampionatToday){
                $result['list_championat'][] = array(
                    'idChampionat' =>$itemsChampionatToday->getChampionat()->getId(),
                    'nomChampionat' => $itemsChampionatToday->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $itemsChampionatToday->getChampionat()->getFullNameChampionat()
                );
            }

        }else{
            return $this->noChampionat();
        }

        $matchsActive = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForToday('active');
        $arrayMatchsActive = array();
        if(is_array($matchsActive) && count($matchsActive)){
            foreach($matchsActive as $kMatchsActive => $itemsMatchsActive){
                $arrayMatchsActive[] = array(
                    'teamsDomicile' => $itemsMatchsActive->getTeamsDomicile()->getFullNameClub(),
                    'teamsVisiteur' => $itemsMatchsActive->getTeamsVisiteur()->getFullNameClub(),
                    'score' => $itemsMatchsActive->getScore(),
                    'scoreDomicile' => substr($itemsMatchsActive->getScore(), 0, 1),
                    'scoreVisiteur' => substr($itemsMatchsActive->getScore(), -1, 1),
                    'live' => ($itemsMatchsActive->getStatusMatch() == 'active') ? true : false,
                    'current-state' => array(
                        'period' => $itemsMatchsActive->getPeriod(),
                        'minute' => $itemsMatchsActive->getMinute()
                    ),
                    'championat' => $itemsMatchsActive->getChampionat()->getId(),
                    'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsActive->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsActive->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                    'statusMatch'=> $itemsMatchsActive->getStatusMatch()
                );
            }
        }

        $matchsNotStart = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForToday('not_started');
        $arrayMatchNotStarted = array();
        if(is_array($matchsNotStart) && count($matchsNotStart) > 0 ){
            foreach($matchsNotStart as $kMatchsNotStarted => $itemsMatchsNotStarted){
               // $arrayMatchNotStarted[] = $itemsMatchsNotStarted;
                $arrayMatchNotStarted[] = array(
                    'idMatch' =>$itemsMatchsNotStarted->getId(),
                    'teamsDomicile' => $itemsMatchsNotStarted->getTeamsDomicile()->getFullNameClub(),
                    'teamsVisiteur' => $itemsMatchsNotStarted->getTeamsVisiteur()->getFullNameClub(),
                    'score' => $itemsMatchsNotStarted->getScore(),
                    'scoreDomicile' => substr($itemsMatchsNotStarted->getScore(), 0, 1),
                    'scoreVisiteur' => substr($itemsMatchsNotStarted->getScore(), -1, 1),
                    'live' => ($itemsMatchsNotStarted->getStatusMatch() == 'active') ? true : false,
                    'current-state' => array(
                        'period' => $itemsMatchsNotStarted->getPeriod(),
                        'minute' => $itemsMatchsNotStarted->getMinute()
                    ),
                    'championat' => $itemsMatchsNotStarted->getChampionat()->getId(),
                    'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsNotStarted->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsNotStarted->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                    'statusMatch'=> $itemsMatchsNotStarted->getStatusMatch(),
                    'heureDebut' => $itemsMatchsNotStarted->getDateMatch()->format("H:i")
                );
            }
        }
        //var_dump($arrayMatchNotStarted); die;
        $matchsEnd = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForToday('finished');
        $arrayMatchsEnd = array();
        if(is_array($matchsEnd) && count($matchsEnd) > 0){
            foreach($matchsEnd as $kMatchsEnd => $itemsMatchsEnd){
                $arrayMatchsEnd[] = array(
                    'teamsDomicile' => $itemsMatchsEnd->getTeamsDomicile()->getFullNameClub(),
                    'teamsVisiteur' => $itemsMatchsEnd->getTeamsVisiteur()->getFullNameClub(),
                    'score' => $itemsMatchsEnd->getScore(),
                    'scoreDomicile' => substr($itemsMatchsEnd->getScore(), 0, 1),
                    'scoreVisiteur' => substr($itemsMatchsEnd->getScore(), -1, 1),
                    'live' => ($itemsMatchsEnd->getStatusMatch() == 'active') ? true : false,
                    'current-state' => array(
                        'period' => $itemsMatchsEnd->getPeriod(),
                        'minute' => $itemsMatchsEnd->getMinute()
                    ),
                    'championat' => $itemsMatchsEnd->getChampionat()->getId(),
                    'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsEnd->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsEnd->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                    'statusMatch'=> $itemsMatchsEnd->getStatusMatch()
                );
            }
        }
       // $matchsArray = array();
          //  $matchsArray = array_merge($arrayMatchsActive, $arrayMatchNotStarted,$arrayMatchNotStarted );
        $matchsArray = $arrayMatchsActive  + $arrayMatchNotStarted + $arrayMatchsEnd;
        /*
        if(is_array($matchsToday) && count($matchsToday) > 0 ){

            foreach($matchsToday as $kMatchsToday => $itemsMatchsToday){
                $result['list_matchs'][] = array(
                    'teamsDomicile' => $itemsMatchsToday->getTeamsDomicile()->getFullNameClub(),
                    'teamsVisiteur' => $itemsMatchsToday->getTeamsVisiteur()->getFullNameClub(),
                    'score' => $itemsMatchsToday->getScore(),
                    'scoreDomicile' => substr($itemsMatchsToday->getScore(), 0, 1),
                    'scoreVisiteur' => substr($itemsMatchsToday->getScore(), -1, 1),
                    'live' => ($itemsMatchsToday->getStatusMatch() == 'active') ? true : false,
                    'current-state' => array(
                        'period' => $itemsMatchsToday->getPeriod(),
                        'minute' => $itemsMatchsToday->getMinute()
                    ),
                    'championat' => $itemsMatchsToday->getChampionat()->getId(),
                    'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsToday->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchsToday->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                    'statusMatch'=> $itemsMatchsToday->getStatusMatch()
                );
            }
        }else{
            return $this->noMatchs();
        }
*/
        $result['list_matchs'] = $matchsArray;

        $result['code_error'] = 0;
        $result['success']= true;
        $result['error'] = false;
        $result['message'] = "Success";
        return new JsonResponse($result);
    }

    private function noChampionat(){
        $result['code_error'] = 4;
        $result['success'] = true;
        $result['error'] = false;
        $result['message'] = "Aucun championat en cours";
        return new JsonResponse($result);
    }
    private function noMatchs(){
        $result['code_error'] = 4;
        $result['success'] = true;
        $result['error'] = false;
        $result['message'] = "Aucun matchs en cours";
        return new JsonResponse($result);
    }
}