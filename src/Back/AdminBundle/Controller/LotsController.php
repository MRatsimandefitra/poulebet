<?php


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Back\AdminBundle\Controller;

/**
 * Description of LotController
 *
 * @author miora.manitra
 */
use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Lot;
use Api\DBBundle\Entity\DroitAdmin;
use Api\DBBundle\Entity\LotCategory;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Session\Session;


class LotsController extends ApiController implements InterfaceDB
{



    public function indexAction(Request $request)
    {
        $session = new Session();
        
        $session->set("current_page","Lots");
        $lots = $this->getAllEntity(self::ENTITY_LOTS);
        $currentDroitAdmin = $this->getDroitAdmin('Lots concours');
        return $this->render('BackAdminBundle:Lots:index.html.twig', array(
            'entities' => $lots,
            'droitAdmin' => $currentDroitAdmin[0]
        ));
    }

    public function addAction(Request $request)
    {
        $lot = new Lot();
        $form = $this->formPost(self::FORM_LOT, $lot);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $image = $lot->getCheminImage();
            $dir = $this->get('kernel')->getRootDir() . '/../web/upload/';
            $filename = time() . "." . $image->guessExtension();
            $image->move($dir, $filename);
            $lot->setCheminImage($filename);
            $lot->setCreatedAt(new \DateTime("now"));
            $this->insert($lot);
            return $this->redirectToRoute("index_lots");
        }
        return $this->render('BackAdminBundle:Lots:add.html.twig', array(
            'form' => $form->createView()));
    }


    public function editLotAction(Request $request, $id)
    {
        $lots = $this->getRepoFormId(self::ENTITY_LOTS, $id);
        $form = $this->formPost(self::FORM_LOT, $lots);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $image = $lots->getCheminImage();
            $dir = $this->get('kernel')->getRootDir() . '/../web/upload/';
            $filename = time() . "." . $image->guessExtension();
            $image->move($dir, $filename);
            $lots->setCheminImage($filename);
            $this->insert($lots, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_lots');
        }
        $currentDroitAdmin = $this->getDroitAdmin('Lots concours');
        return $this->render('BackAdminBundle:Lots:edit_lot.html.twig', array(
            'entities' => $lots,
            'form' => $form->createView(),
            'droitAdmin' => $currentDroitAdmin[0]
        ));
    }

    public function removeLotsAction($id)
    {
        $lots = $this->getRepoFormId(self::ENTITY_LOTS, $id);
        $this->remove($lots);
        return $this->redirectToRoute('index_lots');
    }

    private function getDroitAdmin($droit)
    {

        return $this->get('roles.manager')->getDroitAdmin($droit);
    }

    public function addLotsCategoryAction(Request $request){

        $categoryLots = new LotCategory();
        $form= $this->formPost(self::FORM_LOT_CATEGORY, $categoryLots);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($categoryLots);
            return $this->redirectToRoute("index_lots_category");
        }
        $currentDroitAdmin = $this->getDroitAdmin('Lots concours');
        return $this->render('BackAdminBundle:Lots:add_lot_category.html.twig', array(
            'form' => $form->createView(),
            'droitAdmin' => $currentDroitAdmin[0]
        ));
    }
    public function listLotCategoryAction(){
        $lotCategory = $this->getAllEntity(self::ENTITY_LOT_CATEGORY);
        $currentDroitAdmin = $this->getDroitAdmin('Lots concours');
        return $this->render('BackAdminBundle:Lots:list_lot_category.html.twig', array(
            'entities' => $lotCategory,
            'droitAdmin' => $currentDroitAdmin[0]
        ));
    }
    public function editLotCategoryAction(Request $request, $id){
        $categoryLots = $this->getRepoFormId(self::ENTITY_LOT_CATEGORY, $id);
        $form= $this->formPost(self::FORM_LOT_CATEGORY, $categoryLots);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($categoryLots);
            return $this->redirectToRoute("index_lots_category");
        }
        $currentDroitAdmin = $this->getDroitAdmin('Lots concours');
        return $this->render('BackAdminBundle:Lots:edit_lot_category.html.twig', array(
            'droitAdmin' => $currentDroitAdmin[0],
            'form' => $form->createView()
        ));
    }

    public function removeLotCategoryAction($id){
        $categoryLots = $this->getRepoFormId(self::ENTITY_LOT_CATEGORY, $id);
        $this->remove($categoryLots);
        return $this->redirectToRoute('index_lots_category');
    }
}
