<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ClassementController extends ApiController implements InterfaceDB
{
    public function postGetListClassementAction(Request $request){
        $time = $request->get('time');
        if(is_null($time)){
            return $this->noClassement();
        }
        if($time ==='now'){
            $now = new \DateTime('now');
            $monday = $now->modify('last monday');
            $sunday = $now->modify('next sunday');
            $this->getRepo(self::ENTITY_MATCHS)->findClassement($monday, $sunday);

        }elseif($time === 'last'){

        }elseif($time === 'global'){

        }
        $result = array();


        return new JsonResponse($result);
    }

    public function noClassement(){
        $result['code_error'] = 4;
        $result['success'] = false;
        $result['error'] = true;
        $result['message'] = "Le time doit être spécifié";
        return new JsonResponse($result);
    }
}
