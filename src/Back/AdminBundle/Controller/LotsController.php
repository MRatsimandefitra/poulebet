<?php

namespace Back\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LotsController extends Controller
{
    const ENTITY_LOT = 'ApiDBBundle:Lot';
    const FORM_LOT = 'Api\DBBundle\Form\LotType';

    public function listLotAction()
    {

        return $this->render('BackAdminBundle:Lots:list_lot.html.twig', array(
            // ...
        ));
    }

    public function addLotAction()
    {
        return $this->render('BackAdminBundle:Lots:add_lot.html.twig', array(
            // ...
        ));
    }

    public function editLotAction($id)
    {
        return $this->render('BackAdminBundle:Lots:edit_lot.html.twig', array(
            // ...
        ));
    }

    public function removeLotAction($id)
    {
        return $this->render('BackAdminBundle:Lots:remove_lot.html.twig', array(
            // ...
        ));
    }

}
