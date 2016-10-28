<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Region;
use Symfony\Component\HttpFoundation\Request;

class RegionController extends ApiController implements InterfaceDB
{
    public function indexRegionAction()
    {
        $aRegion = $this->getAllEntity(self::ENTITY_REGION);
        
        return $this->render('BackAdminBundle:Region:index_region.html.twig', array(
            'regions' => $aRegion
        ));
    }

    public function addRegionAction(Request $request)
    {
        $region = new Region();
        $form= $this->formPost(self::FORM_REGION, $region);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($region, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_region');
        }
        return $this->render('BackAdminBundle:Region:add_region.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editRegionAction($id, Request $request)
    {
        $region = $this->getRepoFormId(self::ENTITY_REGION, $id);
        $form= $this->formPost(self::FORM_REGION, $region);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($region, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_region');
        }
        return $this->render('BackAdminBundle:Region:edit_region.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteRegionAction($id)
    {
        $region = $this->getRepoFormId(self::ENTITY_REGION, $id);
        $this->remove($region);
        return $this->redirectToRoute('index_region');
    }
}
