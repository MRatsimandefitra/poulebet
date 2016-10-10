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
        $time = $request->request->get('time');

        $result = array();


        return new JsonResponse($result);
    }
}
