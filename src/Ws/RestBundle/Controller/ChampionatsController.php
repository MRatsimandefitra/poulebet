<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class ChampionatsController extends ApiController
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';

    /**
     * Ws, récupérer la liste des championnats qui ont des matchs
     * @ApiDoc(
     *  description="Ws, récupérer la liste des championnats qui ont des matchs",
     * )
     */
    public function getListeChampionatWithMatchAction()
    {
        $data = $this->getRepo(self::ENTITY_MATCHS)->getListChampionatWithMatch();
        $result = array();
        if ($data) {
            foreach ($data as $k => $vData) {
                $result['list_championat'][] = array(
                    'nomChampionat' => $vData->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $vData->getChampionat()->getFullNameChampionat(),
                    'season' => $vData->getChampionat()->getSeason()
                );
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucun resultat n'a été trouvé";
        }
        return new JsonResponse($result);
    }

    /**
     *
     */
    public function postListeMatchsBySelectedChampionatAction(Request $request)
    {
        $championat = $request->request->get('championat');
        $date = $request->request->get('date');

        $data = $this->getRepo(self::ENTITY_MATCHS)->getListeMatchsBySelectedChampionat($championat, $date);
       // $data = $this->getRepo(self::ENTITY_MATCHS)->findMatchPronosticByParameter($championat, $date, true);
        $result = array();
        if ($data) {
            foreach ($data as $vData) {
                $result['championat'] = $vData->getChampionat()->getFullNameChampionat();
                $result['list_match'][] = array(
                    'id' => $vData->getId(),
                    'dateMatch' => $vData->getDateMatch(),
                    'equipeDomicile' => $vData->getEquipeDomicile(),
                    'equipeVisiteur' => $vData->getEquipeVisiteur(),

                    'logoDomicile' => ''.$this->getParameter('url_poulebet').'/images/Flag-foot/' . $vData->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => ''.$this->getParameter('url_poulebet').'/images/Flag-foot/' . $vData->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                    'score' => $vData->getScore(),
                    'scoreDomicile' => substr($vData->getScore(), 0, 1),
                    'scoreVisiteur' => substr($vData->getScore(), -1, 1),
                    'status' => $vData->getStatusMatch(),
                    'cote_pronostic_1' => $vData->getCot1Pronostic(),
                    'cote_pronostic_n' => $vData->getCoteNPronistic(),
                    'cote_pronostic_2' => $vData->getCote2Pronostic(),
                    'master_prono_1' => $vData->getMasterProno1(),
                    'master_prono_n' => $vData->getMasterPronoN(),
                    'master_prono_2' => $vData->getMasterProno2(),
                    'tempsEcoules' => $vData->getTempsEcoules(),
                    'live' => ($vData->getStatusMatch() == 'active') ? true : false,
                    'current-state' => array(
                        'period' => $vData->getPeriod(),
                        'minute' => $vData->getMinute()
                    ),
                    'voteTotal' => $this->getTotalVoteParMatch($vData->getId()),
                );
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }
        return new JsonResponse($result);
    }


    /**
     * Ws, recuperer la liste des matchs avec tous les chamiponat + les pronostic et cote
     * @ApiDoc(
     *      description="Ws, recuperer la liste des matchs avec tous les chamiponat + les pronostic et cote",
     *      parameters = {
     *          {"name" = "date", "dataType"="date" ,"required"=false, "description"= "date of matchs lets mec ...."}
     *      }
     * )
     */
    public function postListeMatchsByAllChampionatAction(Request $request){

        $date = $request->request->get('date');

        $data = $this->getRepo(self::ENTITY_MATCHS)->getListMatchWithAllChampionat($date);
        $result = array();
        if ($data) {
            foreach ($data as $vData) {
                $result['championat'] = $vData->getChampionat()->getFullNameChampionat();
                $result['list_match'][] = array(
                    'id' => $vData->getId(),
                    'dateMatch' => $vData->getDateMatch(),
                    'equipeDomicile' => $vData->getEquipeDomicile(),
                    'equipeVisiteur' => $vData->getEquipeVisiteur(),

                    'logoDomicile' => ''.$this->getParameter('url_poulebet').'/images/Flag-foot/' . $vData->getCheminLogoDomicile() . '.png',// $vData->getTeamsDomicile()->getLogo(),
                    'logoVisiteur' => ''.$this->getParameter('url_poulebet').'/images/Flag-foot/' . $vData->getCheminLogoVisiteur() . '.png',// $vData->getTeamsVisiteur()->getLogo(),
                    'score' => $vData->getScore(),
                    'scoreDomicile' => substr($vData->getScore(), 0, 1),
                    'scoreVisiteur' => substr($vData->getScore(), -1, 1),
                    'status' => $vData->getStatusMatch(),
                    'cote_pronostic_1' => $vData->getCot1Pronostic(),
                    'cote_pronostic_n' => $vData->getCoteNPronistic(),
                    'cote_pronostic_2' => $vData->getCote2Pronostic(),
                    'master_prono_1' => $vData->getMasterProno1(),
                    'master_prono_n' => $vData->getMasterPronoN(),
                    'master_prono_2' => $vData->getMasterProno2(),
                    'tempsEcoules' => $vData->getTempsEcoules(),
                    'live' => ($vData->getStatusMatch() == 'active') ? true : false,
                    'current-state' => array(
                        'period' => $vData->getPeriod(),
                        'minute' => $vData->getMinute()
                    ),
                    'voteTotal' => $this->getTotalVoteParMatch($vData->getId()),
                );
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }
        return new JsonResponse($result);
    }

    /**
     * GET liste pays with champonat with match
     * @ApiDoc(
     *  description = "Liste des pays qui ont des championat avec des matchs"
     * )
     */
    public function getListePaysWithChampionatWithMatchAction()
    {

        $data = $this->getRepo(self::ENTITY_MATCHS)->getListePaysWithChampionatWithMatch();
        $result = array();
        $dataName = array();

        if ($data) {
            foreach ($data as $k => $vData) {
                /* foreach ($vData->getChampionat()->getPays() as $ktp => $vDataTp) {
                     if (!in_array($vDataTp->getName(), $dataName)) {
                         $dataName[] = $vDataTp->getName();
                     }
                 }*/
                if (!in_array($vData->getChampionat()->getPays(), $dataName)) {
                    $dataName[] = $vData->getChampionat()->getPays();
                }

            }

            $result['pays'] = $dataName;
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }
        return new JsonResponse($result);
    }

    /**
     *
     * List liste des championat par pays si existe match
     *
     * @ApiDoc(
     *      description = "liste des championat avec match par pays ",
     *      requirements = {
     *          {"name"="pays", "dataType"="string", "required"= true, "description" = "Nom du pays à chercher"}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getListeChampionatWithMatchByPaysAction(Request $request)
    {
        $pays = $request->request->get('pays');
        $data = $this->getRepo(self::ENTITY_MATCHS)->getListeChampionatWithMatchByPays($pays);
        $result = array();
        $dataName = array();
        if ($data) {
            $ancienName = "";

            foreach ($data as $vData) {

                if ($ancienName != $vData->getChampionat()->getNomChampionat()) {
                    $dataName[] = array(
                        'nomChampionat' => $vData->getChampionat()->getNomChampionat(),
                        'fullNameChampionat' => $vData->getChampionat()->getFullNameChampionat()
                    );
                }
                $ancienName = $vData->getChampionat()->getNomChampionat();
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }
        $result['list_championat'] = $dataName;
        return new JsonResponse($result);
    }

    public function getListeMatchByChampionat(Request $request)
    {
        $championat = $request->request->get('championat');
        $data = $this->getRepo(self::ENTITY_MATCHS)->getListeMatchByChampionat($championat);
        $result[] = array();
        if ($data) {
            foreach ($data as $vData) {

            }
        }
    }


    public function getListePaysWithChampionatAvecMatchsAction(Request $request)
    {

        $dql = "SELECT m from ApiDBBundle:Matchs m
               LEFT JOIN m.championat ch
               LEFT JOIN ch.teamsPays tp
               WHERE  ch.isEnable = true order by m.dataMatch ASC, m.id ASC";
        /*CURRENT_DATE() BETWEEN ch.dateDebutChampionat and ch.dateFinaleChampionat*/

        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
        $data = $query->getResult();
        if ($data) {
            $dataNamePays = array();
            $ancienNamePays = '';
            foreach ($data as $kData => $vData) {

                foreach ($vData->getChampionat()->getTeamsPays() as $kTeamsPays => $vTeamsPays) {
                    //$dataNamePays[] = $vTeamsPays->getName();
                    if (!in_array($vTeamsPays->getName(), $dataNamePays)) {
                        $dataNamePays[] = $vTeamsPays->getName();
                    }
                    /*if($ancienNamePays != $vTeamsPays->getName()){
                        $dataNamePays[] = $vTeamsPays->getName();
                    }
                    $ancienNamePays =  $vTeamsPays->getName();*/
                }

            }
            foreach ($dataNamePays as $kDataNamePays => $vDataNamePays) {
                $dqlChampinat = "SELECT m from ApiDBBundle:Matchs m
                       LEFT JOIN m.championat ch
                       LEFT JOIN ch.teamsPays tp
                       WHERE ch.isEnable = true
                       AND tp.name LIKE :namePays";
                $queryDqlChampionat = $this->get('doctrine.orm.entity_manager')->createQuery($dqlChampinat);
                $queryDqlChampionat->setParameter('namePays', $vDataNamePays);
                $dataChampionat = $queryDqlChampionat->getResult();

                if ($dataChampionat) {
                    //    var_dump($dataChampionat); die;
                    // $json['pays'][] = $vDataNamePays;
                    $ancienNameChampionat = '';
                    foreach ($dataChampionat as $kDataChampionat => $vDataChampionat) {
                        if ($ancienNameChampionat != $vDataChampionat->getChampionat()->getNomChampionat()) {
                            $json['pays'][$vDataNamePays]['list_championat'][] = $vDataChampionat->getChampionat()->getNomChampionat();
                        }
                        $ancienNameChampionat = $vDataChampionat->getChampionat()->getNomChampionat();

                    }
                }
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = 'success';
        } else {
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = 'Aucun resultat n\'a été trouvé';
        }

        $result['data'] = $json;
        return new JsonResponse($result);
    }
    private function getTotalVoteParMatch($idMatch)
    {
        $dqlVoteUtilisateur = "SELECT v, m from ApiDBBundle:VoteUtilisateur v LEFT JOIN v.matchs m WHERE m.id = :idmatch";
        $queryVoteUtilisateur = $this->get('doctrine.orm.entity_manager')->createQuery($dqlVoteUtilisateur);
        $queryVoteUtilisateur->setParameter('idmatch', $idMatch);
        $dataVote = $queryVoteUtilisateur->getResult();
        $result = count($dataVote);
        return $result;
    }
}
