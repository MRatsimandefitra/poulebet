<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UtilisateurController extends ApiController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_ADMIN = 'ApiDBBundle:Admin';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const FORM_UTILISATEUR = 'Api\DBBundle\Form\UtilisateurType';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    const FORM_DROIT_ADMIN = 'Api\DBBundle\Form\DroitAdminType';

    public function indexAction(Request $request)
    {
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
    }

    public function detailsAction($id)
    {
        $entity = $this->getRepoFormId(self::EENTITY_ADMIN, $id);
        return $this->render('BackAdminBundle:Utilisateur:details.html.twig', array(
            'entity' => $entity
        ));
    }*/

    public function listDroitAction()
    {
        $droits = $this->getAllRepo(self::ENTITY_DROIT);
        return $this->render('BackAdminBundle:Utilisateur:list_droit.html.twig', array(
            'entities' => $droits
        ));
    }
}
