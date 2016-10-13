<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Proxies\__CG__\Api\DBBundle\Entity\MvtCredit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AchatController extends ApiController implements InterfaceDB
{
    /**
     * Ws, Get list oeufs
     * @ApiDoc(
     *  description="Ws, Get liste oeufs à acheter ",
     *   parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=false, "description"= "Token de l'utilisateur "}
     *      }
     * )
     */
    public function postGetListOeufsAction(Request $request){
        $token  = $request->request->get('token');
        if(!$token){
            return $this->noToken();
        }
        $oeufs = $this->getAllEntity(self::ENTITY_ACHAT);
        if(!$oeufs){
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "success";
        }

        $result = array();
        foreach($oeufs as $kOeuf => $itemsOeufs){

            $result['achats'][] = array(
                'image' => "dplb.arkeup.com/images/achats/".$itemsOeufs->getImageOeuf(),
                'productSKU' => $itemsOeufs->getProductSKU(),
                'tarifOeuf' => $itemsOeufs->getTarifOeufs(),
                'tarifEuro' => $itemsOeufs->getTarifEuro()
            );
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
           return $this->noUser();
        }

        $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
        $idLast = $lastSolde[0][1];
        $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
        if($mvtCreditLast){
            $result['solde'] = $mvtCreditLast->getSoldeCredit();
        }else{
            $result['solde'] = 0;
            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucun credit";
        }
        return new JsonResponse($result);
    }

    /**
     * Ws, Insert achat orufs
     * @ApiDoc(
     *  description="Ws, Insertion achat oeufs",
     *   parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=false, "description"= "Token de l'utilisateur "},
     *          {"name" = "oeufs", "dataType"="string" ,"required"=false, "description"= "Token de l'utilisateur "}
     *      }
     * )
     */
    public function postGetInsertAchatsAction(Request $request){
        $token = $request->request->get('token');
        $oeufs = (int) $request->request->get('oeufs');
        if(!$token){
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
                return $this->noUser();
        }
        $productSKU = $request->request->get('productSKU');
        if(!$productSKU){
            return $this->noProductSKU();
        }
        $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
        $idLast = $lastSolde[0][1];
        $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
        $lastCredit = $mvtCreditLast->getSoldeCredit();
        try{
            $newMvtCredit = new MvtCredit();
            $newMvtCredit->setEntreeCredit($oeufs);
            $newMvtCredit->setSoldeCredit($lastCredit + $oeufs);
            $newMvtCredit->setUtilisateur($user);
            $newMvtCredit->getDateMvt(new \DateTime('now'));
            $this->insert($newMvtCredit, array('success' => 'success', 'error' => 'error') );

            $result['code_error'] = 0;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Success";
            return new JsonResponse($result);

        }catch(Exception $e){
            $result['code_error'] = 2;
            $result['error'] = true;
            $result['success'] = false;
            $result['message'] = "Error";
            return new JsonResponse($result);
        }



    }

    private function noToken(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le token doit etre précisé";
        return new JsonResponse($result);
    }

    private function noProductSKU(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le product SKU devrait être précisé";
        return new JsonResponse($result);
    }
    private function noUser(){
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Aucun utilisateur";
        return new JsonResponse($result);
    }
}

