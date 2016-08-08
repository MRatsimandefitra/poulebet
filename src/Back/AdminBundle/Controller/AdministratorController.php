<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\DroitAdmin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdministratorController extends ApiController
{
    const ENTITY_ADMIN = 'ApiDBBundle:Admin';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const FORM_ADMIN = 'Api\DBBundle\Form\AdminType';
    const FORM_ADMIN_DROIT = 'Api\DBBundle\Form\DroitAdminType';
    const FORM_ADMIN_DROIT_ADD_ROLES = 'Api\DBBundle\Form\DroitAdminRoleType';

    public function indexAction()
    {
        $administrator = $this->getAllRepo(self::ENTITY_ADMIN);

        foreach ($administrator as $vAdministrator) {
            var_dump($this->getRepo(self::ENTITY_DROIT_ADMIN)->getDroitByAdminId(2));
            die;
        }
        //$roles = $this->getRepoFrom(self::ENTITY_DROIT_ADMIN, array(''))
        return $this->render('BackAdminBundle:Administrator:index.html.twig', array(
            'entities' => $administrator,

        ));
    }

    public function listRolesAction($idAdmin)
    {
        $entity = $this->getRepoFrom(self::ENTITY_DROIT_ADMIN, array('admin' => $this->getRepoFormId(self::ENTITY_ADMIN, $idAdmin)));
        if (!$entity) {
            return $this->redirectToRoute('add_roles_administrator');
        }

        return $this->render('BackAdminBundle:Administrator:list_roles.html.twig', array(
            'entities' => $entity,
            'idAdmin' => $idAdmin
        ));
    }

    public function addRolesAction(Request $request)
    {
        //var_dump($request->getContent()); die;
        $droitAdmin = new DroitAdmin();
        $form = $this->formPost(self::FORM_ADMIN_DROIT_ADD_ROLES, $droitAdmin);
        $droitAdmin->setAdmin($this->getUser());
        $form->handleRequest($request);
        if ($form->isValid()) {

            $this->get('doctrine.orm.entity_manager')->persist($droitAdmin);
            $this->get('doctrine.orm.entity_manager')->flush();
            return $this->redirectToRoute('index_administrator');
        }
        return $this->render('BackAdminBundle:Administrator:add_roles.html.twig', array(
            'form' => $form->createView()
        ));
    }


    public function  editDroitRolesAdminAction(Request $request, $idAdmin, $idDroitAdmin)
    {
        $entity = $this->getRepoFormId(self::ENTITY_DROIT_ADMIN, $idDroitAdmin);
        if (!$entity) {

        }
        $form = $this->formPost(self::FORM_ADMIN_DROIT, $entity);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $this->insert($entity, array('success' => 'success', 'error' => 'error'));

        }
        return $this->render('BackAdminBundle:Administrator:edit_droit_roles.html.twig', array(
            'form' => $form->createView(),
            'idDroitAdmin' => $idDroitAdmin,
            'idAdmin' => $idAdmin
        ));
    }









    public function editAction(Request $request, $id)
    {

        //  $administrator = $this->getRepoFormId(self::ENTITY_ADMIN, $id);
        $droit = $this->getAllRepo(self::ENTITY_DROIT);
        $user = $this->getUser();

        foreach ($droit as $k => $vDroit) {
            if (!$this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findBy(array('droit' => $vDroit, 'admin' => $user))) {
                $adminDroit = new DroitAdmin();
                $adminDroit->setAdmin($user);
                $adminDroit->setDroit($vDroit);
                $this->get('doctrine.orm.entity_manager')->persist($adminDroit);
                $this->get('doctrine.orm.entity_manager')->flush();
            }
        }

        $form = $this->formPost(self::FORM_ADMIN_DROIT, null);
        $form->handleRequest($request);
        $params = $request->getContent();

        if ($params) {
            $exp = explode("&", str_replace('=on', '', $params));

            foreach ($exp as $k => $v) {
                $war[] = explode('_', $v);
            }

            foreach ($war as $k => $v) {
                $array[] = array(
                    'champ' => $v[0],
                    'id' => $v[1],
                );
            }
            /*echo "<PRE>";
            print_r($array);
            echo "</pre>";
            die;*/
            foreach ($array as $kA => $vA) {
                $droitE = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT)->find($vA['id']);

                $dAdminEntity = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findOneBy(array('droit' => $droitE));
                if ($vA['champ'] == 'lecture') {
                    $dAdminEntity->setLecture(true);
                }
                if ($vA['champ'] == 'suppression') {
                    $dAdminEntity->setSuppression(true);
                }
                if ($vA['champ'] == 'ajout') {
                    $dAdminEntity->setAjout(true);
                }
                if ($vA['champ'] == 'modification') {
                    $dAdminEntity->setModification(true);
                }

                // $this->get('doctrine.orm.entity_manager')->persist($dAdminEntity);
                $this->get('doctrine.orm.entity_manager')->flush();
                //

            }
            die('okok');
        }

        $droitAdminData = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser()));

        /*if($form->isValid()){

            //$this->insert($administrator, array('success' => 'success' , 'error' => 'error'));

        }*/
        return $this->render('BackAdminBundle:Administrator:edit.html.twig', array(
            'form' => $form->createView(),
            'droit' => $droit,
            'id' => $this->getUser()->getId(),
            'droitAdminData' => $droitAdminData
        ));
    }

}
