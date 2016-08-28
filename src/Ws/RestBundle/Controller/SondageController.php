<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\VoteUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class SondageController extends ApiController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';
    const ENTITY_VOTE = 'ApiDBBundle:VoteUtilisateur';

    public function getVoteUsersAction(Request $request)
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
                $oldVote = $this->getRepo(self::ENTITY_MATCHS);
            }

            $voteUtilisateur->setVote($oldVote + 1);

        } catch (Exception $e) {

        }


    }

}
