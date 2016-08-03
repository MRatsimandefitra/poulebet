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

    public function indexAction()
    {
        $administrator = $this->getAllRepo(self::ENTITY_ADMIN);

        return $this->render('BackAdminBundle:Administrator:index.html.twig', array(
            'entities' => $administrator,

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

            foreach ($array as $kA => $vA) {

                $droitE = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT)->find($vA['id']);
                $dAdminEntity = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_DROIT_ADMIN)->findOneBy(array('droit' => $droitE));

                if ($vA['champ'] == 'lecture') {
                    $dAdminEntity->setLecture(true);
                }
                if ($vA['champ'] == 'suppression') {
                    $dAdminEntity->setSuppression(true);
                    die('suppression');
                }
                if ($vA['champ'] == 'ajout') {
                    $dAdminEntity->setAjout(true);
                    die('ajout');
                }
                if ($vA['champ'] == 'modification') {
                    $dAdminEntity->setModification(true);
                    die('modification');
                }

                //$this->get('doctrine.orm.entity_manager')->persist($dAdminEntity);

                //  $this->get('doctrine.orm.entity_manager')->flush();

            }

            die('ies man');
            return $this->redirectToRoute('index_administrator');
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
