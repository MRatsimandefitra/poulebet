<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\MvtCredit;
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
     *      description = "Achat prono > Inerrer un  achat prono ",
     *      parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=true, "description"= "Token pour utilisateur connecte"},
     *          {"name" = "montant", "dataType"="integer" ,"required"=true, "description"= "Montant à inserer"},
     *
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function insertStatusAchatPronoAction(Request $request){
        $token = $request->request->get('token');
        $result = array();
        if(!$token){
            return $this->noToken();
        }
        $userCurrent = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$userCurrent){
            return $this->noUser();
        }
        $montant = (int) $request->request->get('montant');

        if(is_null($montant) or $montant == 0 or $montant < 0){
            return $this->noMontant();
        }

        if($userCurrent->setAchatProno(true)){
            $this->getEm()->flush();
            $mvtCredit = new MvtCredit();
            $mvtCredit->setTypeCredit("PAYEMENT PRONOSTIC");
            $mvtCredit->setUtilisateur($userCurrent);
            $mvtCredit->setSortieCredit($montant);

            // last Solde
            $credit = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('utilisateur' => $userCurrent));

            if (!empty($credit)) {
               // var_dump($credit[0]); die;
                if(is_object($credit[0])){
                    $idLast = $credit[0]->getId();
                }else{
                    $idLast = $credit[0][1];
                }


                $solde = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));

                foreach ($solde as $kCredit => $itemsCredit) {

                    $dernierSolde = $itemsCredit->getSoldeCredit();
                }

            } else {
                $dernierSolde = 0;
            }
            $soldeCredit = $dernierSolde - $montant;
            if($soldeCredit <= 0){
                $soldeCredit = 0;
            }
            $mvtCredit->setSoldeCredit($soldeCredit);
            $this->getEm()->persist($mvtCredit);
            $this->getEm()->flush();

            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
            return new JsonResponse($result);
        }else{
            $result['code_error'] = 2;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Error insertion achat prono";
            return new JsonResponse($result);
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

    private function noMontant(){
        $result['success'] = false;
        $result['error'] = true;
        $result['code_error'] = 2;
        $result['message'] = "Le montant doit être specifie";
        return new JsonResponse($result);
    }
}
