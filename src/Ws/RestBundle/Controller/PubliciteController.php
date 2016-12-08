<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PubliciteController extends ApiController implements InterfaceDB
{
    /**
     * Ws, recuperer les publicite
     * @ApiDoc(
     *  description="Ws, recuperer les publicite",
     *   parameters = {
     *          {"name" = "isPopup", "dataType"="boolean" ,"required"=true, "description"= " Popup = 1 banniere = 0 "},
     *      }
     * )
     */
    public function postGetPubliciteAction(Request $request){
        $isPopup = $request->get('isPopup');
        if(is_null($isPopup)){
            return $this->noIsPopup();
        }
        $pub = $this->getObjectRepoFrom(self::ENTITY_PUB, array('isPopup' => $isPopup));
        $result = array();
        if(is_object($pub)){
            $result['cheminPub'] = 'http://'.$this->getParameter('url_poulebet').'/upload/admin/publicite/'.$pub->getCheminPub();
            $result['isPopup'] = $pub->getIsPopup();
            $result['success'] = true;
            $result['error'] = false;
            $result['code_error'] = 0;
            $result['message'] = "Success";
            return new JsonResponse($result);

        }else{
            return $this->noPub();
        }
    }

    private function noIsPopup(){
        $result['code_error'] = 2;
        $result['success'] = true;
        $result['error'] = false;
        $result['message'] = "Le isPopup doit être spécifier";
        return new JsonResponse($result);
    }

    private function noPub(){
        $result['code_error'] = 0;
        $result['success'] = true;
        $result['error'] = false;
        $result['message'] = "Aucune publicité à spécifier";
        return new JsonResponse($result);
    }
}
