<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\AddressLivraison;
use Api\DBBundle\Entity\MvtLot;
use Api\DBBundle\Entity\MvtCredit;
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
            return $this->sendJsonErrorMsg("La catégorie doit etre précisé");
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
            return $this->sendJsonErrorMsg("Le lot doit etre précisé");
        }
        if(!$token){
            return $this->sendJsonErrorMsg("Le token doit etre précisé");
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->sendJsonErrorMsg("Aucun utilisateur");
        }
        $lot = $this->getRepo(self::ENTITY_LOTS)->findOneById($lotId);
        $output = array();
        if($lot){
            // last Solde
            $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->getLastByUser($user);
            if($lastSolde !== false){
                $dernierSolde = $lastSolde->getSoldeCredit();            
            } else {
                $dernierSolde = 0;            
            }
            $output['response'] = false;
            $quantity = $lot->getQuantity();
            if(($dernierSolde >= $lot->getNbPointNecessaire()) && ($quantity > 0)){
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
            return $this->sendJsonErrorMsg("Le token doit etre précisé");
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->sendJsonErrorMsg("Aucun utilisateur");
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
        $lastSolde = $this->getRepo(self::ENTITY_MVT_CREDIT)->getLastByUser($user);
        if($lastSolde){
            $result['credit'] = $lastSolde->getSoldeCredit();            
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
     *          {"name" = "ville", "dataType"="string" ,"required"=true, "description"= "Ville de livraison "},
     *          {"name" = "id_pays", "dataType"="int" ,"required"=true, "description"= "ID du pays de livraison "},
     *          {"name" = "voie", "dataType"="string" ,"required"=true, "description"= "voie de livraison "},
     *          {"name" = "id_region", "dataType"="int" ,"required"=true, "description"= "ID de la région de livraison "},
     *          {"name" = "codePostal", "dataType"="string" ,"required"=true, "description"= "codePostal de livraison "},
     *          {"name" = "nomComplet", "dataType"="string" ,"required"=true, "description"= "Nom complet "},
     *          {"name" = "numero", "dataType"="string" ,"required"=true, "description"= "Numero de livraison "},
     *          {"name" = "id_lot", "dataType"="int" ,"required"=true, "description"= "ID du lot "}
     *      }
     * )
     */
    public function postInsertAddressLivraisonAction(Request $request){
           
        $ville = $request->request->get('ville');
        if(!$this->checkParamWs($ville)){
            return $this->sendJsonErrorMsg("La ville doit etre précisé");
        }
        $paysId = $request->request->get('id_pays');
        $pays = $this->checkParamWs($paysId,self::ENTITY_PAYS);
        if($pays === false){
            return $this->sendJsonErrorMsg("Le pays doit etre précisé");
        } 
        $voie = $request->request->get('voie');
        if(!$this->checkParamWs($voie)){
            return $this->sendJsonErrorMsg("La voie doit etre précisé");
        }
        $regionId = $request->request->get('id_region');
        $region = $this->checkParamWs($regionId,self::ENTITY_REGION);
        if($region === false){
            return $this->sendJsonErrorMsg("La région doit etre précisé");
        }
        $codePostal = $request->request->get('codePostal');
        if(!$this->checkParamWs($codePostal)){
            return $this->sendJsonErrorMsg("Le code postal doit etre précisé");
        }
        $nomComplet = $request->request->get('nomComplet');
        if(!$this->checkParamWs($nomComplet)){
            return $this->sendJsonErrorMsg("Le nom complet doit etre précisé");
        }
        $numero = $request->request->get('numero');
        if(!$this->checkParamWs($numero)){
            return $this->sendJsonErrorMsg("Le numéro doit etre précisé");
        }

        $token = $request->request->get('token');
        if(!$this->checkParamWs($token)){
            return $this->sendJsonErrorMsg("Le token doit etre précisé");
        }
        
        $lotId = $request->request->get('id_lot');
        $lot = $this->checkParamWs($lotId,self::ENTITY_LOTS);
        if($lot === false){
            return $this->sendJsonErrorMsg("Le lot doit etre précisé");
        }
        
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->sendJsonErrorMsg("Aucun utilisateur");
        }
        try{
            $lastSolde = $this->checkIfUserCanBuy($user, $lot->getNbPointNecessaire());
            if($lastSolde === false){
                return $this->sendJsonErrorMsg("Crédit insuffisant");
            } 
            $quantity = $lot->getQuantity();
            if($quantity === 0){
                return $this->sendJsonErrorMsg("Quantité insuffisante");
            }
            //lot
            $solde = (int) $quantity - 1;
            $mvtLot = $this->addMvtLot($user, $lot, $solde, 1, 0);
            //credit
            $this->addMvtCredit($user, $lot, $lastSolde);
            
            //mails
            $admins = $this->getRepo(self::ENTITY_ADMIN)->findAll();
            $admin = null;
            foreach($admins as $item){
                $roles = $item->getRoles();
                foreach($roles as $role){
                    if ($role == "ROLE_SUPER_ADMIN"){
                        $admin=$item;
                        break;
                    }
                }
                if($admin){
                    break;
                }
            }
            $parameter = $this->getParameterMail();            
            if($parameter){
                $subject = ($parameter->getSubjectAchatLot()) ? $parameter->getSubjectAchatLot() : 'Echange de lot';
                $template = $this->container->get("templating")->render(
                        'ApiCommonBundle:Email:defaultTplLotAdmin.html.twig',
                        array(
                            'lot' => $lot,
                            'nomComplet'  => $nomComplet,
                            'numero' => $numero,
                            'voie'  => $voie,
                            'ville' => $ville,
                            'region' => $region,
                            'pays'   => $pays
                        )
   
                );
                //all admin
                //foreach($admins as $admin){
                    
                //}
                
                if($admin->isEnabled()){
                    $this->sendMail($admin, $subject, $template,$parameter);                          
                }
                $now =new \DateTime('now');
                $message = $parameter->getTemplateAchatLot();
                $message = $this->processBddTemplating($message, array(
                    '{{lot.nomLong}}' => $lot->getNomLong(),
                    '{{logo}}' => $request->getSchemeAndHttpHost() . '/bundles/apitheme/img/poulebet.gif',
                    '{{lot.image}}' => $request->getSchemeAndHttpHost().'/upload/lots/'.$lot->getCheminImage(),
                    '{{date}}'      => $now->format('Y')
                    )
                );
                //customer
                $this->sendMail($user, $subject, $message,$parameter);                                       
            }
            
            $addressLivraison = new AddressLivraison();
            $addressLivraison->setCodePostal($codePostal);
            $addressLivraison->setNomcomplet($nomComplet);
            $addressLivraison->setNumero($numero);
            $addressLivraison->setVoie($voie);
            $addressLivraison->setVille($ville);
            $addressLivraison->setPays($pays);
            $addressLivraison->setRegion($region);
            $addressLivraison->setUser($user);
            $addressLivraison->setLot($lot);
            $this->insert($addressLivraison, array('success' => 'success' , 'error' => 'error'));
            //update address and set mvtLot
            $addressLivraison->setMvtLot($mvtLot);
            $this->getEm()->persist($addressLivraison);
            $this->getEm()->flush();
            
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
    
    /**
     * Replace params in template 
     * 
     * @param string $template
     * @param array $params
     * @return string
     */
    protected function processBddTemplating($template,$params){
        foreach($params as $param => $value){
            $template = str_replace($param,$value,$template);
        }
        return $template;
    }
    
    protected function getParameterMail() {
       $params = $this->getEm()->getRepository('ApiDBBundle:ParameterMail')->findAll();
       if($params){
           return $params[count($params)-1];
       }
       return false;
    }
    
    protected function addMvtCredit($user,$lot,$lastSolde){
        $mvtCredit = new MvtCredit();
        $mvtCredit->setTypeCredit("ACHAT LOT");
        $mvtCredit->setUtilisateur($user);
        $mvtCredit->setSortieCredit($lot->getNbPointNecessaire());
        $mvtCredit->setDateMvt(new \DateTime('now'));
        $soldeCredit = $lastSolde - $lot->getNbPointNecessaire();
        if($soldeCredit <= 0){
            $soldeCredit = 0;
        }
        $mvtCredit->setSoldeCredit($soldeCredit);
        $this->getEm()->persist($mvtCredit);
        return $mvtCredit;
    }
    
    /**
     * Add MvtLot for Lot entity
     * 
     * @param string $token
     * @param Lot $lot
     * @param int $out output
     * @return MvtLot
     */
    protected function addMvtLot($user,$lot,$solde,$out,$input){
        $mvtLot = new MvtLot();
        $mvtLot->setLot($lot);
        $mvtLot->setUtilisateur($user);
        $mvtLot->setSortieLot($out);
        $mvtLot->setSoldeLot($solde);
        $mvtLot->setEntreeLot($input);
        $this->getEm()->persist($mvtLot);
        return $mvtLot;
    }
}
