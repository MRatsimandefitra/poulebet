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
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('token' => $token));
        if(!$user){
            return $this->noUser();
        }

        $result = array();
        if($user && is_object($user)){
            $result['account'] = array(
                'photo'=> 'http://dplb.arkeup.com/upload/utilisateur/'. $user->getCheminPhoto(),
                'nom' => $user->getNom(),
                'prenom' => $user->getPrenom(),
            );
        }

        // gains
        $gains = $this->getRepo(self::ENTITY_MATCHS)->findTotalGainsOfUser($user->getId(), true);
        if(is_array($gains) && count($gains) > 0){
            $totalGains = 0;
            foreach($gains as $kGains => $itemsGains){
                $totalGains = $totalGains + $itemsGains->getGainPotentiel();
            }
            $result['totalGain'] = $totalGains;
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Success";
            return new JsonResponse($result);
        }else{
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucun gains ";
        }

        //recapitulation par utilisateur
        $recapitulation = $this->getRepo(self::ENTITY_MATCHS)->findRecapitulationForUser($user->getId());
        if(is_array($recapitulation)&& count($recapitulation) > 0){

        }else{
            $result['code_error']= 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucune récapitulatio trouvée";
        }
        return new JsonResponse($result);
    }

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
}
