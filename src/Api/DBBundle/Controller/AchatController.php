<?php

namespace Api\DBBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AchatController extends Controller
{
    public function indexAchatAction()
    {
        return $this->render('ApiDBBundle:Achat:index_achat.html.twig', array(
            // ...
        ));
    }

    public function addAchatAction()
    {
        return $this->render('ApiDBBundle:Achat:add_achat.html.twig', array(
            // ...
        ));
    }

    public function editAchatAction($id)
    {
        return $this->render('ApiDBBundle:Achat:edit_achat.html.twig', array(
            // ...
        ));
    }

    public function deleteAchatAction($id)
    {
        return $this->render('ApiDBBundle:Achat:delete_achat.html.twig', array(
            // ...
        ));
    }

}
