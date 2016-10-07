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
    /*public function postGetAllMathsRecapAction(Request $request){

        $isCombined = (bool) $request->request->get('isCombined');
        if($isCombined === NULL){
            return $this->noCombined();
        }
        $result = array();
        $matchs = $this->getRepo(self::ENTITY_MATCHS)->findMatchsForRecap();

        return new JsonResponse($result);
    }

    public function noCombined(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = true;
        $result['message'] = " IsCombined doit être specifie";
        return new JsonResponse($result);
    }*/
}

