<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\VoteUtilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends ApiController
{
    const ENTITY_VOTE = 'ApiDBBundle:Vote';

    /**
     *
     * Ws insérer un vote utilisateur
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function insertVoteAction(Request $request){
        $vote = $request->request->get('vote');
        $result = array();
        $idUtilisateur = $request->request->get('idUtilisateur');
        $utilisateur = $this->getRepo(self::ENTITY_UTILISATEUR)->find($idUtilisateur);
        $voteUtilisateurData = $this->getRepo(self::ENTITY_VOTE)->findOneBy(array('utilisateur' => $utilisateur));
        $vote = $vote + $voteUtilisateurData->getVote();
        if(!$voteUtilisateurData){
            $voteUtilisateurData = new VoteUtilisateur();
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";

        }else{
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucune donné n'a été trouvé";
        }
            $voteUtilisateurData->setVote($vote);
            $this->insert($voteUtilisateurData, array('success' => 'success' , 'error' => 'error'));

        return new JsonResponse($result);

    }

    /**
     * Ws, récupérer les votes des utilisateurs
     *
     */
    public function getVoteUtilisateurAction(Request $request){
        $idUtilisateur = $request->request->get('utilisateur');

        $utilisateur = $this->getRepoFormId(self::ENTITY_UTILISATEUR, $idUtilisateur);
        $data = $this->getRepo(self::ENTITY_VOTE)->findOneBy(array('utilisateur' => $utilisateur));
        $result = array();
        if($data){
            foreach($data as $vData){

            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucune donné n'a été trouvé";
        }else{
            $result['code_error'] = 4;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Aucune donné n'a été trouvé";
        }
        return new JsonResponse($result);
    }
}
