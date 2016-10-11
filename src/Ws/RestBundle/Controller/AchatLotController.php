<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\AddressLivraison;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AchatLotController extends ApiController implements InterfaceDB
{
    public function postGetListLotAction(Request $request){
        $token = $request->request->get('token');
        if(!$token){
            return $this->noToken();
        }
        $user = $this->getObjectRepoFrom(self::ENTITY_UTILISATEUR, array('userTokenAuth' => $token));
        if(!$user){
            return $this->noUser();
        }
        $result = array();
        $lots = $this->getAllEntity(self::ENTITY_LOTS);
        $category = $this->getRepo(self::ENTITY_LOTS)->findCategoryLot();
        if($category){
            foreach($category as $kCategory => $itemsCategory){
                $result['category_lot'][] = array(
                    'category' => $itemsCategory->getCategory()
                );
            }
        }else{

        }
        if($lots){

            foreach($lots as $kLots => $itemsLots){
                $result['list_lot'][] = array(
                    'idLot' => $itemsLots->getId(),
                    'nomLot' => $itemsLots->getNomLot(),
                    'nbPointNecessaire' => $itemsLots->getId(),
                    'description' => $itemsLots->getDescription(),
                    'image' => 'dplb.arkeup.com/images/upload/'.$itemsLots->getCheminImage(),
                    'idLotCategory' => $itemsLots->getLotCategory()->getId(),
                );
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
        $lastCredit = $mvtCreditLast->getSoldeCredit();
        $result['credit'] = $lastCredit;
        return new JsonResponse($result);
    }

    public function postInsertAddressLivraisonAction(Request $request){
        $ville = $request->request->get('ville');
        if(!$ville){
            return $this->noVille();
        }
        $pays = $request->request->get('pays');
        if(!$pays){
            return $this->noPays();
        }
        $voie = $request->request->get('voie');
        if(!$voie){
            return $this->noVoie();
        }
        $region = $request->request->get('region');
        if(!$region){
            return $this->noRegion();
        }
        $codePostal = $request->request->get('codePostal');
        if(!$codePostal){
            return $this->noCodePostal();
        }
        $nomComplet = $request->request->get('nomComplet');
        if(!$nomComplet){
            return $this->noName();
        }
        $numero = $request->request->get('numero');
        if(!$numero){
            return $this->noNumero();
        }

        $token = $request->request->get('token');
        if(!$token){
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
}