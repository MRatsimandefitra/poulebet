<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Component\HttpFoundation\Request;

class AddressLivraisonController extends ApiController implements InterfaceDB
{
    public function editAction($id, Request $request)
    {
        $addressLivraison = $this->getRepoFormId(self::ENTITY_ADDRESS_LIVRAISON, $id);
        $form= $this->formPost(self::FORM_ADDRESS_LIVRAISON, $addressLivraison);
        $form->handleRequest($request);
        if($form->isValid()){
            $lot = $addressLivraison->getLot();
            $this->insert($addressLivraison, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('history_lots',array('id' => $lot->getId()));
        }
        return $this->render('BackAdminBundle:AddressLivraison:edit.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
