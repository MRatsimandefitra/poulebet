<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class RecapitulationController extends ApiController implements InterfaceDB
{
    public function postGetAllMathsAction(Request $requests){

        $result = array();

        return new JsonResponse($result);
    }
}
