<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\VoteUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class SondageController extends ApiController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';
    const ENTITY_VOTE = 'ApiDBBundle:VoteUtilisateur';

    /**
     * @ApiDoc(
     *      description  = "insertion vote utilisateurs ",
     *      parameters = {
     *          { "name" = "token", "dataType" = "string", "required" = true, "desccription" = "token utilkisateur en cours" },
     *          { "name" = "vote", "dataType" = "integer", "required" = true, "desccription" = "Vote valeur 1 pour equipe 1 , N pour matchs null, 2 pour equipe 2" },
     *          { "name" = "mathId", "dataType" = "integer", "required" = true, "desccription" = "ID of Matchs" },
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function insertVoteUsersAction(Request $request)
    {
        $token = $request->request->get('token');
        $vote = $request->request->get('vote');
        $MatchId = $request->request->get('matchId');
        $currentUser = $this->getRepo(self::ENTITY_UTILISATEUR)->findOneByUserToken($token);

        $currentMatch = $this->getRepo(self::ENTITY_MATCHS)->find($MatchId);
        try {
            $voteUtilisateur = $this->getRepo(self::ENTITY_VOTE)->findOneBy(array('utilisateur' => $currentUser, 'matchs' => $currentMatch));
            $new = false;
            if (!$voteUtilisateur) {
                $voteUtilisateur = new VoteUtilisateur();
                $new = true;
            }

            $voteUtilisateur->setMatchs($currentMatch);
            $voteUtilisateur->setUtilisateur($currentUser);
            $voteUtilisateur->setGagnant(false);
            $voteUtilisateur->setVote($vote);
            if ($new) {
                $this->getEm()->persist($voteUtilisateur);
            }
            $this->getEm()->flush();
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";

        } catch (Exception $e) {
            $result['code_error'] = 2;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Error";
        }

        return new JsonResponse($result);

    }

    /**
     * @ApiDoc(
     *      description = "Mise à jour vite utilisateur"
     * )
     * @param Request $request
     */
    public function updateVoteUsersAction(Request $request)
    {

    }

    /**
     * @ApiDoc(
     *      description = "Listes des matchs dans sondage, a noter que les matchs sont des matchs issue des match dans les concours
     * )
     */
    public function getAllSondageAction(Request $request)
    {

        die('okok');
        /* $dqlMatchConcour = "SELECT m from ApiDBBundle:Matchs m ";
         $query = $this->get('doctrine.orm.entity_manager')->createQuery($dqlMatchConcour);
         $data = $query->getResult();

         $return = array();
         if($data){
             foreach($data as $kItems => $vItems){
                 var_dump($vItems);
             }
         }else{
             $result['code_error'] = 2;
             $result['success'] = false;
             $result['error'] = true;
             $result['message'] = "Error";
         }*/
        /*return new JsonResponse($return);*/
    }

    public function postToGetAllMatchsSondageAction(Request $request)
    {
        $token = $request->request->get('token');
        $dqlChampionat = "SELECT m, ch from ApiDBBundle:Matchs m JOIN m.concours co LEFT JOIN m.championat ch GROUP BY ch.nomChampionat";
        $queryChampionat = $this->get('doctrine.orm.entity_manager')->createQuery($dqlChampionat);
        $dataChampionat = $queryChampionat->getResult();
        if ($dataChampionat) {

            foreach ($dataChampionat as $kChampionat => $vChampionatItems) {

                $result['list_championat'][] = array(
                    'idChampionat' => $vChampionatItems->getChampionat()->getId(),
                    'nomChampionat' => $vChampionatItems->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $vChampionatItems->getChampionat()->getFullNameChampionat(),
                    'season' => $vChampionatItems->getChampionat()->getSeason()
                );
            }

        }

        $dqlMatch = "SELECT m from ApiDBBundle:Matchs m JOIN m.concours co LEFT JOIN m.championat ch ";
        $queryMatch = $this->get('doctrine.orm.entity_manager')->createQuery($dqlMatch);
        $data = $queryMatch->getResult();

        // vote total
        $dqlVote = "SELECT co from ApiDBBundle:Concours co  LEFT JOIN co.matchs m";
        $queryVote = $this->get('doctrine.orm.entity_manager')->createQuery($dqlVote);
        $dataVote = $queryVote->getResult();
        $nbTotalVote = count($dataVote) + 1;
        // vote utilisateur en cours
        $currentUser = $this->getRepo(self::ENTITY_UTILISATEUR)->findOneByUserToken($token);
        //$currentMatch = $this->getRepo(self::ENTITY_MATCHS)->find($MatchId);

        /*$dataVote = $this->getTotalVote();
        $nbTotalVote = count($dataVote);
        foreach ($dataVote as $keyVote => $vVoteItems) {
            $vote = $vVoteItems->getVote();
        }*/

        if ($data) {
            foreach ($data as $KeyMatchs => $matchsItems) {
                $this->getVoteIdUser($matchsItems->getId(), $currentUser->getUserToken());
                $result['matchs'][] = array(
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
                    'current-state' => array(
                        'period' => $matchsItems->getPeriod(),
                        'minute' => $matchsItems->getMinute()
                    ),
                    /*'is_vote' => ($vote)? true : false,*/
                    /*'vote' => $vote,*/
                    'vote' => $this->getVoteIdUser($matchsItems->getId(), $token),
                    'voteTotal' => $this->getTotalVoteParMatch($matchsItems->getId()),
                    'pourcentage1' => $this->getPourcentage(1, $matchsItems->getId(), $token),
                    'pourcentageN' => $this->getPourcentage(0, $matchsItems->getId(), $token),
                    'pourcentage2' => $this->getPourcentage(2,$matchsItems->getId(), $token),
                    'championat' => $matchsItems->getChampionat()->getId()

                );
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = true;
            $result['message'] = "Sucess";
        } else {
            $result['code_error'] = 2;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucun resultat trouvé";
        }

        return new JsonResponse($result);
    }

    public function getVoteIdUser($idMatch, $token)
    {
        $dql = "SELECT vo, m, u from ApiDBBundle:VoteUtilisateur vo LEFT JOIN vo.matchs m LEFT JOIN vo.utilisateur u
                WHERE m.id = :idMatch AND u.userToken = :token";
        $query = $this->get('doctrine.orm.entity_manager')->createQuery($dql);
        $query->setParameters(array('idMatch' => $idMatch, 'token' => $token));
        $response = $query->getResult();
        if ($response) {
            foreach ($response as $kVote => $voteItems) {
                $result = $voteItems->getVote();
            }
        } else {
            $result = null;
        }
        return $result;

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

    private function getPourcentage($value, $idMatch, $token = null)
    {
        $dqlPourcentage = "SELECT vo, m, u from ApiDBBundle:VoteUtilisateur vo LEFT JOIN vo.matchs m LEFT JOIN vo.utilisateur u
                           WHERE vo.vote = :vote AND m.id = :idMatch";
        $queryPourcentage = $this->get('doctrine.orm.entity_manager')->createQuery($dqlPourcentage);
        //$queryPourcentage->setParameter('vote', $value);
         $queryPourcentage->setParameters(array('vote' => $value, 'idMatch' => $idMatch/*, 'token' => $token*/));
        $dataPourcentage = $queryPourcentage->getResult();
        $nbPourcentage = count($dataPourcentage);

        $totalVote = $this->getTotalVoteParMatch($idMatch);

        $pourcentage = ($nbPourcentage / $totalVote) * 100;

        return $pourcentage;
    }
}
