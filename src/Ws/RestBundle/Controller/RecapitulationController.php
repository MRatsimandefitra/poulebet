<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\CommonBundle\Fixed\InterfaceResponseWs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RecapitulationController extends ApiController implements InterfaceDB
{
    public function postGetListRecapAction(Request $request){

        $isCombined = (bool) $request->request->get('isCombined');
        if($isCombined === NULL){
            return $this->noCombined();
        }
        $token = $request->request->get('token');
        if($isCombined === NULL){
            return $this->noToken();
        }
        $user  = $this->getRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));


        die('okok');
        $result = array();
        $nbRecap = $this->getRepo(self::ENTITY_MATCHS)->findNbMatchsForRecap($user->getId());
        //$matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForRecap($user);

        return new JsonResponse($result);
    }

    private function noToken(){
        $result = $this->no();
        $result['message'] = " Token doit être specifie";
        return new JsonResponse($result);
    }
    private function noCombined(){
        $result = $this->no();
        $result['message'] = " IsCombined doit être specifie";
        return new JsonResponse($result);
    }
    private function no(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = true;
        return $result;
    }
}

