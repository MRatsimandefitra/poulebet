<?php
namespace Ws\RestBundle\Response;

use Api\CommonBundle\Fixed\InterfaceResponseWs;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResponseWs implements  InterfaceResponseWs {

    public function noCombined(){
        $response['code_error'] = 2;
        return new JsonResponse($response);
    }
}