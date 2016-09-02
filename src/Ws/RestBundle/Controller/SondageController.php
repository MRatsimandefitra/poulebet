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
     *          { "name" = "token", "dataType" = "string", "required" = true, "desccription" = "token" },
     *          { "name" = "isvote", "dataType" = "boolean", "required" = true, "desccription" = "isvote" },
     *          { "name" = "mathId", "dataType" = "integer", "required" = true, "desccription" = "ID of Matchs" },
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function insertVoteUsersAction(Request $request)
    {
        $token = $request->request->get('token');
        $isVote = $request->request->get('isvote');
        $MatchId = $request->request->get('matchId');
        $currentUser = $this->getRepo(self::ENTITY_UTILISATEUR)->findOneByUserToken($token);
        $currentMatch = $this->getRepo(self::ENTITY_MATCHS)->find($MatchId);
        try {
            $voteUtilisateur = new VoteUtilisateur();
            $voteUtilisateur->setMatchs($currentMatch);
            $voteUtilisateur->setUtilisateur($currentUser);
            $voteUtilisateur->setGagnant(false);
            if ($isVote) {
                $oldVote = $this->getRepo(self::ENTITY_MATCHS)->find($MatchId);
                if(!$oldVote){
                    $oldVote = 0;
                }
            }

            $voteUtilisateur->setVote($oldVote + 1);
            $this->insert($voteUtilisateur, array('success' => 'success' , 'error' => 'error'));
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
    public function updateVoteUsersAction(Request $request){

    }

}
