<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends ApiController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const FORM_UTILISATEUR = 'Api\DBBundle\Form\UtilisateurType';

    public function indexAction()
    {
        $utilisateur = $this->getRepo(self::ENTITY_UTILISATEUR)->findAll();

        return $this->render('BackAdminBundle:Utilisateur:index.html.twig', array(
            'entities' => $utilisateur
        ));
    }

    public function updateAction(Request $request, $id)
    {
        $entity = $this->getRepoFormId(self::ENTITY_UTILISATEUR, $id);
        $form = $this->formPost(self::FORM_UTILISATEUR, $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->insert($entity, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('details_admin_utilisateur', array('id' => $id));
        }
        return $this->render('BackAdminBundle:Utilisateur:update.html.twig', array(
            'entity' => $entity
        ));
    }

    public function removeAction($id)
    {
        $this->remove($this->getRepoFormId(self::ENTITY_UTILISATEUR, $id));
        return $this->redirectToRoute('index_admin_utilisateur');
    }

    public function detailsAction($id)
    {
        $entity = $this->getRepoFormId(self::ENTITY_UTILISATEUR, $id);
        return $this->render('BackAdminBundle:Utilisateur:details.html.twig', array(
            'entity' => $entity
        ));
    }
}
