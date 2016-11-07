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
        $dateDebut =null;
        $dateFinale = null;
        $params = array();
        if($request->getContent()){
            if($request->request->get('dateDebut')){
                $dateDebut = $request->request->get('dateDebut');
                $params['dateDebut'] = $dateDebut;
            }
            if($request->request->get('dateFinale')){
                $dateFinale = $request->request->get('dateFinale');
                $params['dateFinale'] = $dateFinale;
            }
        }


        if($dateDebut != null && $dateFinale != null){
            //var_dump($dateDebut); die;
            $classemenGroupUser= $this->getRepo(self::ENTITY_MATCHS)->findClassement($dateDebut,$dateFinale,null,true);
        }else{
            $classemenGroupUser= $this->getRepo(self::ENTITY_MATCHS)->findClassement(null,null,null,true);
        }
        $result = array();
        foreach($classemenGroupUser as $kGroupUser => $itemsGroupUser){
            if($dateDebut && $dateFinale){
                $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement($dateDebut, $dateFinale, $itemsGroupUser->getUtilisateur()->getId());
            }else{
                $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement(null, null, $itemsGroupUser->getUtilisateur()->getId());
            }
            $total= 0;
            foreach($classement as $kClassement => $itemsClassement){
                $total = $total + $itemsClassement->getClassement();
                $nom = $itemsClassement->getUtilisateur()->getNom();
                $prenom  = $itemsClassement->getUtilisateur()->getPrenom();
                $photo = $itemsClassement->getUtilisateur()->getCheminPhoto();
                $tmpResult = array(
                    'id' => $itemsClassement->getUtilisateur()->getId(),
                    'nom' =>   $nom,
                    'prenom' =>  $prenom,
                    'photo' => $photo,
                    'classement' => $total
                );

            }
            $resultTmp[] = $tmpResult;


        }

        $arrayClasssement=  array();
        if(!empty($resultTmp)){
            foreach($resultTmp as $kResult => $itemsResult){
                $arrayClasssement[] = $itemsResult['classement'];
            }
            arsort($arrayClasssement);
            foreach($arrayClasssement as $kArrayClassement => $itemsArrayClassement){
                if($itemsArrayClassement == $resultTmp[$kArrayClassement]['classement']){
                    $result[] = $resultTmp[$kArrayClassement];
                }
            }
        }

        return $this->render('BackAdminBundle:Classement:index_classement.html.twig', array(
          //  'classement' => $classement,
            'result' => $result,
            'params' => $params
        ));
    }

    public function detailsClassementAction(Request $request, $id)
    {
        $classement = $this->getRepo(self::ENTITY_MATCHS)->findClassement(null, null, $id);
        foreach($classement as $itemsClassement){
            $details[] = array(
                'id' => $itemsClassement->getUtilisateur()->getId(),
                'nom' =>   $itemsClassement->getUtilisateur()->getNom(),
                'prenom' =>   $itemsClassement->getUtilisateur()->getPrenom(),
                'photo' => $itemsClassement->getUtilisateur()->getCheminPhoto(),
                'classement' => $itemsClassement->getClassement(),
            );
        }


        return $this->render('BackAdminBundle:Classement:details_classement.html.twig', array(
            'classement' => $classement,
            'details' => $details
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
