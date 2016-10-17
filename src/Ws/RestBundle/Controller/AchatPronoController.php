<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class AchatPronoController extends ApiController implements InterfaceDB
{
    /**
     * @ApiDoc(
     *      description = "Achat prono > Récuperer status si achat prono effectué ",
     *      parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=false, "description"= "Token pour utilisateur connecte"},
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function postGetStatusAction(Request $request){

        $token = $request->request->get('token');
        if(!$token){
            return $this->noToken();
        }
        $userCurrent = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$userCurrent){
            return $this->noUser();
        }
        $isAchatProno = $userCurrent->getAchatProno();
        if($isAchatProno){
            $result['isAchatProno'] = (bool) $isAchatProno;
        }else{
            $result['isAchatProno'] = (bool) $isAchatProno;
        }
        $credit = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($userCurrent->getId());
        if (!empty($credit)) {
            $idLast = $credit[0][1];
            $solde = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
            foreach ($solde as $kCredit => $itemsCredit) {
                $result['solde'] = $itemsCredit->getSoldeCredit();
            }
        } else {
            $result['solde'] = 0;
        }
        $result['success'] = true;
        $result['error'] = false;
        $result['code_error'] = 0;
        $result['message'] = "Success";

        return new JsonResponse($result);
    }

    /**
     * @ApiDoc(
     *      description = "Achat prono > Récuperer status si achat prono effectué ",
     *      parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=false, "description"= "Token pour utilisateur connecte"},
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function insertStatusAchatPronoAction(Request $request){
        $token = $request->request->get('token');
        if(!$token){
            return $this->noToken();
        }
        $userCurrent = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$userCurrent){
            return $this->noUser();
        }
    }
    private function noUser(){
        $result['success'] = true;
        $result['error'] = false;
        $result['code_error'] = 4;
        $result['message'] = "Aucun utilisateur";
        return new JsonResponse($result);

    }

    private function noToken(){
        $result['success'] = false;
        $result['error'] = true;
        $result['code_error'] = 2;
        $result['message'] = "Le token doit être specifie";
        return new JsonResponse($result);
    }

}
