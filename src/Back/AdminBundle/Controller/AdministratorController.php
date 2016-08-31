<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Admin;
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
      //  $droit = $this->getRepo(self::ENTITY_DROIT)->findOneByFonctionnalite('Administration');
        /*$currentDroitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser(), 'droit' => $droit ));*/
        $currentDroitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findDroitAdminByUserConnected($this->getUser());
        if(!$currentDroitAdmin){
            $droit = $this->getAllRepo(self::ENTITY_DROIT);
            $user = $this->getUser();

            foreach ($droit as $k => $vDroit) {
                if (!$this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findBy(array('droit' => $vDroit, 'admin' => $this->getUser()))) {
                    $adminDroit = new DroitAdmin();
                    $adminDroit->setLecture(true);
                    $adminDroit->setModification(false);
                    $adminDroit->setAjout(false);
                    $adminDroit->setSuppression(false);
                    $adminDroit->setAdmin($this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($this->getUser()->getId()));
                    $adminDroit->setDroit($vDroit);
                    $this->get('doctrine.orm.entity_manager')->persist($adminDroit);
                    $this->get('doctrine.orm.entity_manager')->flush();

                }
            }
            #1$currentDroitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findDroitAdminByUserConnected($this->getUser());
            $wsRoles = $this->get('roles.manager');
            $currentDroitAdmin = $wsRoles->getDroitAdmin('Administrator');
        }
        $administrator = $this->getAllRepo(self::ENTITY_ADMIN);
        return $this->render('BackAdminBundle:Administrator:index.html.twig', array(
            'entities' => $administrator,
            'currentAdmin' => $currentDroitAdmin[0]
        ));
    }

    public function editAdministratorProfilAction(Request $request, $id){
        $currentAdmin = $this->getAdministratorById($id);

        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($currentAdmin);

        $currentAdmin->setUsername($currentAdmin->getUsername());
        $form = $this->formPost(self::FORM_ADMIN, $currentAdmin);
        $form->handleRequest($request);
        if($form->isValid()){
            $password = $encoder->encodePassword($form->get('password')->getData(), $currentAdmin->getSalt());
            $currentAdmin->setPassword($password);

            $this->insert($currentAdmin, array('success' => 'success' , 'error' => 'error'));
            return $this->redirectToRoute('index_administrator');
        }
        return $this->render('BackAdminBundle:Administrator:edit_profile.html.twig', array(
           'form' => $form->createView()
        ));
    }


    public function addAdministratorAction(Request $request){
        $administrator = new Admin();
        $factory = $this->get('security.encoder_factory');

        $encoder = $factory->getEncoder($administrator);


        $form = $this->formPost(self::FORM_ADMIN, $administrator);
        $form->handleRequest($request);
        if($form->isValid()){
            $password = $encoder->encodePassword($form->get('password')->getData(), $administrator->getSalt());
            $administrator->setPassword($password);
            $administrator->setEnabled(true);

            $mailer = $this->get('mail.manager');
            $mailer->setSubject("Email de validation");
            $mailer->setFrom("toDefine@gmail.com");
            $mailer->setTo($administrator->getEmail());
            $text =
                "<p>Bonjour</p>
                <p>L'adminitrateur de POULBET a validé votre inscription
                Nous vous invitons à vous connecter avec votre compte.</p>
                <p>Cordialement,</p>
                <p>L'équipe.</p>";

            $mailer->addParams('body',$text);
            $mailer->send();

            $this->insert($administrator, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_administrator');
        }
        return $this->render('BackAdminBundle:Administrator:add_admininstrator.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAdministratorAction($id){
        $currentAdmin = $this->getAdministratorById($id);

        $this->remove($currentAdmin);
        return $this->redirectToRoute('index_administrator');

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


    public function editDroitAdminAction(Request $request, $id)
    {


        $droit = $this->getAllRepo(self::ENTITY_DROIT);
        $user = $this->getUser();

        foreach ($droit as $k => $vDroit) {
            if (!$this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findBy(array('droit' => $vDroit, 'admin' => $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id)))) {
                $adminDroit = new DroitAdmin();
                $adminDroit->setLecture(true);
                $adminDroit->setModification(false);
                $adminDroit->setAjout(false);
                $adminDroit->setSuppression(false);
                $adminDroit->setAdmin($this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id));
                $adminDroit->setDroit($vDroit);
                $this->get('doctrine.orm.entity_manager')->persist($adminDroit);
                $this->get('doctrine.orm.entity_manager')->flush();

            }
        }

        $form = $this->formPost(self::FORM_ADMIN_DROIT, null);
        $form->handleRequest($request);
        $params = $request->getContent();
        //var_dump($params); die;
        // if super_admin posted
        if ($request->get('is_super_admin')) {
            foreach ($droit as $vDroit) {

                $adminDroit = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findOneBy(array('droit' => $vDroit, 'admin' => $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id) ));
                //  var_dump($adminDroit);die;
                //  var_dump($adminDroit); die;
                $adminDroit->setAjout(true);
                $adminDroit->setModification(true);
                $adminDroit->setLecture(true);
                $adminDroit->setSuppression(true);
                 $this->get('doctrine.orm.entity_manager')->persist($adminDroit);
                $this->get('doctrine.orm.entity_manager')->flush();
            }
            $adminSup = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id);
            $adminSup->setRoles(array('ROLE_SUPER_ADMIN'));
            $adminSup->setSuperAdmin(true);
            $this->get('doctrine.orm.entity_manager')->persist($adminSup);
            $this->get('doctrine.orm.entity_manager')->flush();
            return $this->redirectToRoute('edit_roles_admin_droit', array('id' => $id));
        }

        // if params from form exist
        if ($params) {

            foreach ($droit as $vDroit) {
                // var_dump($vDroit); die;
                $adminDroit = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findOneBy(array('droit' => $vDroit, 'admin' => $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id)));
                $adminDroit->setAjout(false);
                $adminDroit->setModification(false);
                $adminDroit->setLecture(false);
                $adminDroit->setSuppression(false);
                // $this->get('doctrine.orm.entity_manager')->persist($adminDroit);
                $this->get('doctrine.orm.entity_manager')->flush();
            }

            $adminSup = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id);
            $adminSup->setSuperAdmin(false);
            $this->get('doctrine.orm.entity_manager')->persist($adminSup);
            $this->get('doctrine.orm.entity_manager')->flush();

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
                //var_dump($droitE);
                $dAdminEntity = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findOneBy(array('droit' => $droitE, 'admin' => $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id)));
                // var_dump($dAdminEntity); die;
                //$dAdminEntity = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findDroitAdminByDroit($vA['id']);
                //var_dump($dAdminEntity);
                /* foreach($dAdminEntity as $vAdminEntity){
                     var_dump($vAdminEntity);
                 }*/
                //  var_dump($dAdminEntity);
                if ($vA['champ'] == 'lecture') {
                    // die('lecture');
                    $dAdminEntity->setLecture(true);
                }
                if ($vA['champ'] == 'suppression') {
                    // die('suppression');
                    $dAdminEntity->setSuppression(true);
                }
                if ($vA['champ'] == 'ajout') {
                    // die('ajout');
                    $dAdminEntity->setAjout(true);
                }
                if ($vA['champ'] == 'modification') {

                    $dAdminEntity->setModification(true);
                }

                $this->get('doctrine.orm.entity_manager')->persist($dAdminEntity);
                $this->get('doctrine.orm.entity_manager')->flush();

            }
        }else{
            foreach ($droit as $vDroit) {
                // var_dump($vDroit); die;
                $adminDroit = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findOneBy(array('droit' => $vDroit, 'admin' => $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id)));
                $adminDroit->setAjout(false);
                $adminDroit->setModification(false);
                $adminDroit->setLecture(false);
                $adminDroit->setSuppression(false);
                // $this->get('doctrine.orm.entity_manager')->persist($adminDroit);
                $this->get('doctrine.orm.entity_manager')->flush();
            }
        }

        $droitAdminData = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findBy(
                array('admin' => $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id)),
                array(
                'droit' => 'ASC',
            ));

        /*if($form->isValid()){

            //$this->insert($administrator, array('success' => 'success' , 'error' => 'error'));

        }*/
        $admin = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->findOneBy(array('id' => $id));
        return $this->render('BackAdminBundle:Administrator:edit.html.twig', array(
            'form' => $form->createView(),
            'droit' => $droit,
            'id' => $this->getUser()->getId(),
            'droitAdminData' => $droitAdminData,
            'admin' => $admin
        ));

    }


    public function validerCompteAction($id){
        $admin = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->find($id);
        $admin->setEnabled(true);
        // envoie mail
        $mailer = $this->get('mail.manager');
        $mailer->setSubject("Email de validation");
        $mailer->setFrom("toDefine@gmail.com");
        $mailer->setTo($admin->getEmail());
        $text =
            "<p>Bonjour</p>
            <p>L'adminitrateur de POULBET a validé votre inscription
            Nous vous invitons à vous connecter avec votre compte.</p>
            <p>Cordialement,</p>
            <p>L'équipe.</p>";
        
        $mailer->addParams('body',$text);
        $mailer->send();
        $this->get('doctrine.orm.entity_manager')->flush();
        return $this->redirectToRoute('edit_roles_admin_droit',array('id'=>$id));

    }

    private function getAdministratorById($id){
        return $this->getRepoFormId(self::ENTITY_ADMIN, $id);
    }
    /* public function editAction(Request $request, $id)
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
         }

         $droitAdminData = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser()));


         $administarateur  = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_ADMIN)->findOneBy(array('id' => $id));

         return $this->render('BackAdminBundle:Administrator:edit.html.twig', array(
             'form' => $form->createView(),
             'droit' => $droit,
             'id' => $this->getUser()->getId(),
             'droitAdminData' => $droitAdminData,
             'admin' => $administarateur
         ));
     }
    */

}
