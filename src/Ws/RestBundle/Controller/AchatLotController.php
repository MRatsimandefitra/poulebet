<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\AddressLivraison;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class AchatLotController extends ApiController implements InterfaceDB
{
    /**
     * Ws, Get list des lots par categorie
     * @ApiDoc(
     *   description="Ws, Get list des lots par categorie",
     *   parameters = {
     *          {"name" = "category_id", "dataType"="int" ,"required"=true, "description"= "ID de la catégorie"}
     *      }
     * )
     */
    public function getListLotCategoryAction(Request $request){
        $categoryId = $request->request->get('category_id');
        if(!$categoryId){
            return $this->noCategory();
        }
        $lots = $this->getRepo(self::ENTITY_LOTS)->getLotsByCategory($categoryId);
        $output = array();
        if(count($lots) > 0){
            foreach($lots as $lot){
                $output['list_lot'][] = array(
                    'idLot' => $lot->getId(),
                    'nomLong' => $lot->getNomLong(),
                    'nbPointNecessaire' => $lot->getId(),
                    'cheminImage' => $request->getHttpHost().'/upload/lots/'.$lot->getCheminImage(),
                    'description' => $lot->getDescription()
                ); 
            }
            $output['code_error'] = 0;
            $output['success'] = true;
            $output['error'] = false;
            $output['message'] = "Success";
        } else {
            $output['code_error'] = 0;
            $output['success'] = true;
            $output['error'] = false;
            $output['message'] = "Aucun lots disponible";
        }
        return new JsonResponse($output);
    }
    
    /**
     * Ws, check if user can buy
     * @ApiDoc(
     *   description="Ws, verifier si l'utilisateur peut acheter le lot",
     *   parameters = {
     *          {"name" = "lot_id", "dataType"="int" ,"required"=true, "description"= "ID du lot"},
     *          {"name" = "token", "dataType"="string" ,"required"=true, "description"= "Token de l'utilisateur "}
     *      }
     * )
     */
    public function checkBuyLotAction(Request $request){
        $lotId = $request->request->get('lot_id');
        $token = $request->request->get('token');
        if(!$lotId){
            return $this->noLot();
        }
        if(!$token){
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->noUser();
        }
        $lot = $this->getRepo(self::ENTITY_LOTS)->findOneById($lotId);
        $output = array();
        if($lot){
            // last Solde
            $credit = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('utilisateur' => $user),array('id' => 'DESC'));
            if (!empty($credit)) {
                if(is_object($credit[0])){
                    $idLast = $credit[0]->getId();
                }else{
                    $idLast = $credit[0][1];
                }
                $soldes = $this->getRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));

                foreach ($soldes as $solde) {
                    $dernierSolde = $solde->getSoldeCredit();
                }
            } else {
                $dernierSolde = 0;
            }
            $output['response'] = false;
            if($dernierSolde >= $lot->getNbPointNecessaire()){
                $output['response'] = true;
            }
            $output['code_error'] = 0;
            $output['success'] = true;
            $output['error'] = false;
            $output['message'] = "Success";
        } else {
            $output['response'] = false;
            $output['code_error'] = 0;
            $output['success'] = true;
            $output['error'] = false;
            $output['message'] = "Aucun lot disponible";
        }
        return new JsonResponse($output);
    }
    
    /**
     * Ws, Get list des lots
     * @ApiDoc(
     *   description="Ws, Get list des lots ",
     *   parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=false, "description"= "Token de l'utilisateur "}
     *      }
     * )
     */
    public function postGetListLotAction(Request $request){
        $token = $request->request->get('token');
        if(!$token){
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->noUser();
        }
        $categoryId = $request->request->get('category_id');
        $nbPoint = $request->request->get('nbPointNecessaire');
        $result = array();
        $lots = $this->getAllEntity(self::ENTITY_LOTS);
        $category = $this->getRepo(self::ENTITY_LOTS)->findCategoryLot();

        if(!empty($category)){
            $categories = array();
            foreach($category as $kCategory => $itemsCategory){

                if($itemsCategory->getLotCategory()){
                    $lotCategory = $itemsCategory->getLotCategory();
                    $categories[$lotCategory->getId()] = array(
                        'category' => $itemsCategory->getLotCategory()->getCategory(),
                        'id' => $itemsCategory->getLotCategory()->getId()
                    );
                }

            }
            ksort($categories);
            foreach($categories as $cat){
                $result['category_lot'][] = $cat;                
            }
        }else{

        }

        if($lots){
            $result['list_lot'] = array();
            $pricesLot = array();
            foreach($lots as $kLots => $itemsLots){
                $lotCategory = $itemsLots->getLotCategory();
                $quantity = $itemsLots->getQuantity();
                $hasFound = true;
                if($quantity > 0){
                    if(!empty($categoryId) && ($categoryId != $lotCategory->getId())){
                        $hasFound = false;
                    }
                    if(!empty($nbPoint) && ($nbPoint != $itemsLots->getNbPointNecessaire())){
                        $hasFound = false;
                    } 
                    $pricesLot[$itemsLots->getNbPointNecessaire()] = $itemsLots->getNbPointNecessaire();
                    if($hasFound) {
                        $result['list_lot'][] = array(
                            'idLot' => $itemsLots->getId(),
                            'nomLot' => $itemsLots->getNomLot(),
                            'nomLong' => $itemsLots->getNomLong(),
                            'nbPointNecessaire' => $itemsLots->getNbPointNecessaire(),
                            'description' => $itemsLots->getDescription(),
                            'image' => $request->getHttpHost().'/upload/lots/'.$itemsLots->getCheminImage(),
                            'idLotCategory' => $lotCategory->getId(),
                            'qteDisponible' => $quantity
                        ); 
                    }
                }
            }
            sort($pricesLot);
            $result['prix_lot'] = array();
            foreach($pricesLot as $price){
                $result['prix_lot'][] = $price;                
            }
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
        }else{
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Aucun lots disponible";
        }
        $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->findLastSolde($user->getId());
        $idLast = $lastSolde[0][1];
        $mvtCreditLast = $this->getObjectRepoFrom(self::ENTITY_MVT_CREDIT, array('id' => $idLast));
        if($mvtCreditLast){
            $lastCredit = $mvtCreditLast->getSoldeCredit();
            $result['credit'] = $lastCredit;            
        } else {
            $result['credit'] = 0;            
        }
        return new JsonResponse($result);
    }

    /**
     * Ws, Insert addresse de livraison
     * @ApiDoc(
     *   description="Ws, Insert addresse de livraison ",
     *   parameters = {
     *          {"name" = "token", "dataType"="string" ,"required"=true, "description"= "Token de l'utilisateur "},
     *          {"name" = "ville", "dataType"="string" ,"required"=false, "description"= "Ville de livraison "},
     *          {"name" = "id_pays", "dataType"="string" ,"required"=false, "description"= "Pays de livraison "},
     *          {"name" = "voie", "dataType"="string" ,"required"=false, "description"= "voie de livraison "},
     *          {"name" = "id_region", "dataType"="string" ,"required"=false, "description"= "region de livraison "},
     *          {"name" = "codePostal", "dataType"="string" ,"required"=false, "description"= "codePostal de livraison "},
     *          {"name" = "nomComplet", "dataType"="string" ,"required"=false, "description"= "Nom complet "},
     *          {"name" = "numero", "dataType"="string" ,"required"=false, "description"= "Numero de livraison "}
     *      }
     * )
     */
    public function postInsertAddressLivraisonAction(Request $request){
        $ville = $request->request->get('ville');
        if(!$this->checkParamWs($ville)){
            return $this->noVille();
        }
        $paysId = $request->request->get('id_pays');
        $pays = $this->checkParamWs($paysId,self::ENTITY_PAYS);
        if($pays === false){
            return $this->noPays();
        } 
        $voie = $request->request->get('voie');
        if(!$this->checkParamWs($voie)){
            return $this->noVoie();
        }
        $regionId = $request->request->get('id_region');
        $region = $this->checkParamWs($regionId,self::ENTITY_REGION);
        if($region === false){
            return $this->noRegion();
        }
        $codePostal = $request->request->get('codePostal');
        if(!$this->checkParamWs($codePostal)){
            return $this->noCodePostal();
        }
        $nomComplet = $request->request->get('nomComplet');
        if(!$this->checkParamWs($nomComplet)){
            return $this->noName();
        }
        $numero = $request->request->get('numero');
        if(!$this->checkParamWs($numero)){
            return $this->noNumero();
        }

        $token = $request->request->get('token');
        if(!$this->checkParamWs($token)){
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->noUser();
        }
        try{
            $addressLivraison = new AddressLivraison();
            $addressLivraison->setCodePostal($codePostal);
            $addressLivraison->setNomcomplet($nomComplet);
            $addressLivraison->setNumero($numero);
            $addressLivraison->setVoie($voie);
            $addressLivraison->setVille($ville);
            $addressLivraison->setPays($pays);
            $addressLivraison->setRegion($region);
            $addressLivraison->setUser($user);
            $this->insert($addressLivraison, array('success' => 'success' , 'error' => 'error'));
            $result['code_error'] = 0;
            $result['success'] = true;
            $result['error'] = false;
            $result['message'] = "Success";
        }catch(Exception $ex){
            $result['code_error'] = 2;
            $result['success'] = false;
            $result['error'] = true;
            $result['message'] = "Error";
        }
        return new JsonResponse($result);
    }
    
    private function checkParamWs($value,$entityClass = '',$param = 'id'){
        if(empty($value)){
            return false;
        }
        if(empty($entityClass)){
            return true;
        }
        $entity = $this->getObjectRepoFrom($entityClass,array($param => $value));
        if(!$entity){
            return false;
        }
        return $entity;
    }
    
    private function noVille(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "La ville doit etre précisé";
        return new JsonResponse($result);
    }
    private function noNumero(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "La ville doit etre précisé";
        return new JsonResponse($result);
    }
    private function noPays(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le numero doit etre précisé";
        return new JsonResponse($result);
    }
    private function noVoie(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "La voie doit etre précisé";
        return new JsonResponse($result);
    }
    private function noRegion(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "La region doit etre précisé";
        return new JsonResponse($result);
    }
    private function noCodePostal(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le code postal  doit etre précisé";
        return new JsonResponse($result);
    }
    private function noName(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le nom complet doit etre précisé";
        return new JsonResponse($result);
    }
    private function noToken(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "Le token doit etre précisé";
        return new JsonResponse($result);
    }

    private function noUser(){
        $result['code_error'] = 0;
        $result['error'] = false;
        $result['success'] = true;
        $result['message'] = "Aucun utilisateur";
        return new JsonResponse($result);
    }
    
    private function noCategory(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "L'ID de la categorie doit etre précisé";
        return new JsonResponse($result);
    }
    
    private function noLot(){
        $result['code_error'] = 2;
        $result['error'] = true;
        $result['success'] = false;
        $result['message'] = "L'ID du lot doit etre précisé";
        return new JsonResponse($result);
    }
}
