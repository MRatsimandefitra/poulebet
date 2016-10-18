<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Oeufs;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AchatController extends ApiController implements InterfaceDB
{
    public function indexAchatAction()
    {
        $lisAchat = $this->getAllEntity(self::ENTITY_ACHAT);
        return $this->render('BackAdminBundle:Achat:index_achat.html.twig', array(
            'achat' => $lisAchat
        ));
    }

    public function addAchatAction(Request $request)
    {
        $achat = new Oeufs();
        $form= $this->formPost(self::FORM_ACHAT, $achat);
        $form->handleRequest($request);
        if($form->isValid()){

            $file = $achat->getImageOeuf();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->get('kernel')->getRootDir().'/../web/images/achats/',
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $achat->setImageOeuf($fileName);
            $this->insert($achat, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_achat');
        }
        return $this->render('BackAdminBundle:Achat:add_achat.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAchatAction($id, Request $request)
    {
        $achat = $this->getRepoFormId(self::ENTITY_ACHAT, $id);
        $form= $this->formPost(self::FORM_ACHAT, $achat);
        $form->handleRequest($request);
        if($form->isValid()){
            $file = $achat->getImageOeuf();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            $file->move(
                $this->get('kernel')->getRootDir().'/../web/images/achats/',
                $fileName
            );

            // Update the 'brochure' property to store the PDF file name
            // instead of its contents
            $achat->setImageOeuf($fileName);
            $this->insert($achat, array('success' => 'success', 'error' => 'error'));
            return $this->redirectToRoute('index_achat');
        }
        return $this->render('BackAdminBundle:Achat:edit_achat.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function deleteAchatAction($id)
    {
        $achat = $this->getRepoFormId(self::ENTITY_ACHAT, $id);
        $this->remove($achat);
        return $this->redirectToRoute('index_achat');

    }

}
