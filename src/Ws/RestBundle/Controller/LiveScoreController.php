<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class LiveScoreController extends ApiController
{
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';

    public function getMatchLiveScoreAction(Request $request)
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->getMatchLiveScore();
        $result = array();
        if ($data) {

            /*    foreach ($data as $k => $vData) {

                    $result['list_match'][] = array(
                             $vData->getChampionat()->getNomChampionat() =>array(
                                 'teamsDomicile' => $vData->getTeamsDomicile()->getFullNameClub(),
                                 'teamsVisiteur' => $vData->getTeamsVisiteur()->getFullNameClub(),
                                 'score' => $vData->getScore(),
                                 'scoreDomicile' => substr($vData->getScore(), 0, 1),
                                 'scoreVisiteur' => substr($vData->getScore(), -1, 1),
                                 'liveTime' => '',
                             )
                    );
                }*/
            $ancienNameChampionat = "";
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
                /*$data['championat'][] = $vChampionat;*/
                foreach ($dataMatchs as $kDataMatchs => $vDataMatchs) {
                    //var_dump($vDataMatchs->getTeamsDomicile()->getFullNameClub()); die;
                    $dataDetails['championat'][$vDataMatchs->getChampionat()->getFullNameChampionat()][] = array(
                        'teamsDomicile' => $vDataMatchs->getTeamsDomicile()->getFullNameClub(),
                        'teamsVisiteur' => $vDataMatchs->getTeamsVisiteur()->getFullNameClub(),
                        'score' => $vDataMatchs->getScore(),
                        'scoreDomicile' => substr($vDataMatchs->getScore(), 0, 1),
                        'scoreVisiteur' => substr($vDataMatchs->getScore(), -1, 1),
                        'liveTime' => '',
                    );
                }

            }
            $result = $dataDetails;
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
}