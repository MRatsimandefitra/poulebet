<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClassementController extends ApiController implements InterfaceDB
{
    public function indexClassementAction(Request $request)
    {

        $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement();
        $total = 0;
        foreach($classement as $itemsClassement){
            $total = $total + $itemsClassement->getClassement();
            $result = array(
                'id' => $itemsClassement->getUtilisateur()->getId(),
                'nom' =>   $itemsClassement->getUtilisateur()->getNom(),
                'prenom' =>   $itemsClassement->getUtilisateur()->getPrenom(),
                'photo' => $itemsClassement->getUtilisateur()->getCheminPhoto(),
                'classement' => $total
            );
        }
        return $this->render('BackAdminBundle:Classement:index_classement.html.twig', array(
            'classement' => $classement,
            'result' => $result
        ));
    }
    public function detailsClassementAction(Request $request, $id)
    {

        $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement();
        $total = 0;
        foreach($classement as $itemsClassement){
            $total = $total + $itemsClassement->getClassement();
            $result = array(
                'id' => $itemsClassement->getUtilisateur()->getId(),
                'nom' =>   $itemsClassement->getUtilisateur()->getNom(),
                'prenom' =>   $itemsClassement->getUtilisateur()->getPrenom(),
                'photo' => $itemsClassement->getUtilisateur()->getCheminPhoto(),
                'classement' => $total
            );
        }
        return $this->render('BackAdminBundle:Classement:details_classement.html.twig', array(
            'classement' => $classement,
        ));
    }

    public function addClassementAction()
    {
        return $this->render('BackAdminBundle:Classement:add_classement.html.twig', array(
            // ...
        ));
    }

    public function editClassementAction($id)
    {
        return $this->render('BackAdminBundle:Classement:edit_classement.html.twig', array(
            // ...
        ));
    }

    public function removeClassementAction($id)
    {
        return $this->render('BackAdminBundle:Classement:remove_classement.html.twig', array(
            // ...
        ));
    }

}
