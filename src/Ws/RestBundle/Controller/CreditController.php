<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CreditController extends ApiController implements InterfaceDB
{
    public function postGetSoldeCreditAction(Request $request){
        $token = $request->request->get('token');
        if(!$token){
            return $this->noToken();
        }
        $user= $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->noUser();
        }

        $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());

        if(is_object($lastSolde[0])){
            $idLast =  $lastSolde[0];
        }else{
            $idLast = $lastSolde[0][1];
        }

        $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
        $result['solde'] = $mvtCreditLast->getSoldeCredit();
        $result['code_error'] = 0;
        $result['success'] = true;
        $result['message'] = "Success";
        return new JsonResponse($result);

    }

    private function noToken(){
        $result['code_error'] = 2;
        $result['success'] = false;
        $result['error'] = true;
        $result['message'] = "Le Tokeb doit etre précisé";
        return new JsonResponse($result);
    }
    private function noUser(){
        $result['code_error'] = 0;
        $result['success'] = true;
        $result['error'] = true;
        $result['message'] = "Aucun utilisateur trouve";
        return new JsonResponse($result);
    }
}
