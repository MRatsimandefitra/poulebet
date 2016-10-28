<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Pays;
use Symfony\Component\HttpFoundation\Request;

class PaysController extends ApiController implements InterfaceDB
{
    public function indexPaysAction()
    {
        $aPays = $this->getAllEntity(self::ENTITY_PAYS);
        
        return $this->render('BackAdminBundle:Pays:index_pays.html.twig', array(
            'pays' => $aPays
        ));
    }

    public function addPaysAction(Request $request)
    {
        $pays = new Pays();
        $form= $this->formPost(self::FORM_PAYS, $pays);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($pays, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_pays');
        }
        return $this->render('BackAdminBundle:Pays:add_pays.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editPaysAction($id, Request $request)
    {
        $pays = $this->getRepoFormId(self::ENTITY_PAYS, $id);
        $form= $this->formPost(self::FORM_PAYS, $pays);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($pays, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_pays');
        }
        return $this->render('BackAdminBundle:Pays:edit_pays.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deletePaysAction($id)
    {
        $pays = $this->getRepoFormId(self::ENTITY_PAYS, $id);
        $this->remove($pays);
        return $this->redirectToRoute('index_pays');
    }
}
