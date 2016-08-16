<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MentionController extends ApiController
{
    public function getAllMentionAction(Request $request){

        $data = "";

        return new JsonResponse(array(

        ));
    }


    public function getMentionLegale(Request $request){

    }

    public function getMentionCGUAction(Request $request){

    }

    public function getMentionCGPSAction(Request $request){

    }
    public function getMentionCGVAction(Request $request){

    }

}
