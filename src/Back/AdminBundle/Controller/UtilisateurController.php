<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Api\DBBundle\Entity\MvtCredit;

class UtilisateurController extends ApiController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_ADMIN = 'ApiDBBundle:Admin';
    const ENTITY_MVTCREDIT = 'ApiDBBundle:MvtCredit';
    const FORM_UTILISATEUR_ADMIN = 'Api\DBBundle\Form\UtilisateurAdminType';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const FORM_UTILISATEUR = 'Api\DBBundle\Form\UtilisateurType';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    const FORM_DROIT_ADMIN = 'Api\DBBundle\Form\DroitAdminType';
    const FORM_MVTCREDIT = 'Api\DBBundle\Form\MvtCreditType';
    
    
    public function indexAction(Request $request)
    {
        //var_dump($request->request); die;
        $tri = $request->get('tri');
        $droit = $this->getRepo(self::ENTITY_DROIT)->findOneByFonctionnalite('Utilisateurs');
        $currentDroitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findOneBy(array('admin' => $this->getUser(), 'droit' => $droit ));
        $champ = $request->get('champ');
        $utilisateur = $this->getRepo(self::ENTITY_UTILISATEUR)->getAllUtilisateurs($champ, $tri);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $utilisateur, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );
        return $this->render('BackAdminBundle:Utilisateur:index.html.twig', array(
            'entities' => $utilisateur,
            'currentAdmin' => $currentDroitAdmin,
            'pagination' => $pagination
        ));
    }

    public function exportCsvUtilisateurAction(){

        $data = $this->getAllRepo(self::ENTITY_UTILISATEUR);
        $name = $this->get('kernel')->getRootDir().'/../web/csv/utilisateurs.csv';
        $handle   = fopen($name, 'w');
        fputcsv($handle, array('Email', 'Utilisateurs', 'Nom', 'Prenom'));
        foreach($data as $value){
            fputcsv($handle, array($value->getEmail(), $value->getUsername(), $value->getNom(), $value->getPrenom()));
        }
        fclose($handle);
        return new Response(
            file_get_contents($name),
            200,
            array(
                'Content-Type'        => 'text/csv; charset=utf-8',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $n = 'liste_utilisateurs.csv')
            )
        );

    }

    public function updateUtilisateurAction(Request $request, $id){

        $entity = $this->getRepoFormId(self::ENTITY_UTILISATEUR, $id);
        $currentAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findDroitAdminByUserConnected($this->getUser());
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($this->getRepoFormId(self::ENTITY_UTILISATEUR, $id));

        $form = $this->formPost(self::FORM_UTILISATEUR_ADMIN, $entity);
        $form->handleRequest($request);
        if($form->isValid()){
            $password = $encoder->encodePassword($entity->getPassword(), $entity->getSalt());
            if(!$entity->getPassword()){
                $entity->setPassword($password);
            }

            $this->insert($entity, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_admin_utilisateur');
        }
        return $this->render('BackAdminBundle:Utilisateur:update.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
            'currentAdmin' => $currentAdmin[0]
        ));
    }


    public function deleteUtilisateurAction($id){
        $currentUtilisateur = $this->getRepoFormId(self::ENTITY_UTILISATEUR, $id);
        $this->remove($currentUtilisateur);
        $this->addFlash(
            'notice',
            'Utilisateur a bien été supprimer'
        );
        return $this->redirectToRoute('index_admin_utilisateur');
    }

    /*public function updateAction(Request $request, $id)
    {
        $entity = $this->getRepoFormId(self::EENTITY_ADMIN, $id);
        $form = $this->formPost(self::FORM_ADMIN, $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->insert($entity, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('details_admin_utilisateur', array('id' => $id));
        }
        return $this->render('BackAdminBundle:Utilisateur:update.html.twig', array(
            'entity' => $entity,
            'form_utilisateur' => $form
        ));
    }

    public function removeAction($id)
    {
        $this->remove($this->getRepoFormId(self::EENTITY_ADMIN, $id));
        return $this->redirectToRoute('index_admin_utilisateur');
    }*/

    public function detailsAction($id)
    {
        $currentAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findDroitAdminByUserConnected($this->getUser());
        $entity = $this->getRepoFormId(self::ENTITY_UTILISATEUR, $id);
        return $this->render('BackAdminBundle:Utilisateur:details.html.twig', array(
            'entity' => $entity,
            'currentAdmin' => $currentAdmin[0]
        ));
    }

    public function listDroitAction()
    {
        $droits = $this->getAllRepo(self::ENTITY_DROIT);
        return $this->render('BackAdminBundle:Utilisateur:list_droit.html.twig', array(
            'entities' => $droits
        ));
    }
    
    public function creditAction($id){
        $droit = $this->getRepo(self::ENTITY_DROIT)->findOneByFonctionnalite('Utilisateurs');
        $currentDroitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findOneBy(array('admin' => $this->getUser(), 'droit' => $droit ));
        $user = $this->getRepo(self::ENTITY_UTILISATEUR)->find($id);
        $mvtCredit = $this->getRepo(self::ENTITY_MVTCREDIT)->findByUtilisateur($user);
        return $this->render("BackAdminBundle:Utilisateur:mvtCredit.html.twig", array(
            'mvtCredit' =>$mvtCredit,
            'utilisateur' =>$user,
            'currentAdmin'=>$currentDroitAdmin
        ));
    }
    public function addMvtCreditAction(Request $request){
        $entity = new MvtCredit();
        $id = $request->get('id_utilisateur');
        $utilisateur = $this->getRepo(self::ENTITY_UTILISATEUR)->find($id);
        $form = $this->formPost(self::FORM_MVTCREDIT, $entity);
        $form->handleRequest($request);
        if($form->isValid()){
            // initialisation des données
            $entity->setEntreeCredit($entity->getCredit()->getTypeCredit());
            $entity->setDateMvt(new \DateTime('now'));
            $entity->setUtilisateur($utilisateur);
            $solde = $entity->getEntreeCredit()-$entity->getSortieCredit();
            $entity->setSoldeCredit($solde);
            //insertion 
            $this->insert($entity, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('mvtCredit_utilisateur',array('id'=>$id));
        }
        return $this->render('BackAdminBundle:Utilisateur:add_MvtCredit.html.twig', array(
            'form' => $form->createView(),
            'utilisateur'=>$utilisateur
        ));
    }
    public function updateMvtCreditAction(Request $request){
        $id_MvtCredit = $request->get("id_mvtCredit");
        $entity = $this->getRepo(self::ENTITY_MVTCREDIT)->find($id_MvtCredit);
        $form = $this->formPost(self::FORM_MVTCREDIT, $entity);
        $form->handleRequest($request);
        if($form->isValid()){
            // initialisation des données
            $entity->setEntreeCredit($entity->getCredit()->getTypeCredit());
            $solde = $entity->getEntreeCredit()- $entity->getSortieCredit();
            $entity->setSoldeCredit($solde);
            //insertion 
            $this->insert($entity, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('mvtCredit_utilisateur',array('id'=>$entity->getUtilisateur()->getId()));
        }
        return $this->render('BackAdminBundle:Utilisateur:update_mvtCredit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function removeMvtCreditAction($id_mvtCredit){
        $entity = $this->getRepo(self::ENTITY_MVTCREDIT)->find($id_mvtCredit);
        $this->remove($entity);
        return $this->redirectToRoute('mvtCredit_utilisateur',array('id'=>$entity->getUtilisateur()->getId()));
        
    }
}
