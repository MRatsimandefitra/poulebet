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
use Api\DBBundle\Entity\Lot;
use Api\DBBundle\Entity\DroitAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class LotsController extends ApiController{
    const ENTITY_LOTS = 'ApiDBBundle:Lot';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const FORM_LOT = 'Api\DBBundle\Form\LotType';
    const FORM_ADMIN_DROIT = 'Api\DBBundle\Form\DroitAdminType';
    const FORM_ADMIN_DROIT_ADD_ROLES = 'Api\DBBundle\Form\DroitAdminRoleType';
    
    
    public function indexAction(Request $request){
        $lots = $this->getAllEntity(self::ENTITY_LOTS);
        $currentDroitAdmin = $this->getDroitAdmin('Lots concours');
        return $this->render('BackAdminBundle:Lots:index.html.twig', array(
            'entities' => $lots,
            'droitAdmin' => $currentDroitAdmin[0]
        ));
    }
    public function addAction(Request $request){
        $lot = new Lot();
        $form = $this->formPost(self::FORM_LOT, $lot);
        $form->handleRequest($request);
        if($form->isValid()){
            $image = $lot->getCheminImage();
            $dir = $this->get('kernel')->getRootDir().'/../web/upload/';
            $filename = time().".".$image->guessExtension();
            $image->move($dir,$filename);
            $lot->setCheminImage($filename);
            $lot->setCreatedAt(new \DateTime("now"));
            $this->insert($lot);
            return $this->redirectToRoute("index_lots");
        }
        return $this->render('BackAdminBundle:Lots:add.html.twig', array(
            'form' => $form->createView()));
    }
    private function getDroitAdmin($droit){
        $droitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser(), 'droit' => $this->getRepo(self::ENTITY_DROIT)->findOneBy(array('fonctionnalite' => $droit))));
        return $droitAdmin;
    }
    
}
