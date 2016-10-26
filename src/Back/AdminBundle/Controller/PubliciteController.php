<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\Publicite;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PubliciteController extends ApiController implements InterfaceDB
{
    public function indexPubliciteAction()
    {
        $pubs = $this->getAllEntity(self::ENTITY_PUB);
        return $this->render('BackAdminBundle:Publicite:index_publicite.html.twig', array(
            'pub' => $pubs
        ));
    }

    public function addPubliciteAction(Request $request)
    {
        $pub = new Publicite();
        $form = $this->formPost(self::FORM_PUB, $pub);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($pub, array(
                'success' => $this->translate('success.pub.add'),
                'error' => $this->translate('error.pub.add')
            ));
            return $this->redirectToRoute('index_publicite');
        }
        return $this->render('BackAdminBundle:Publicite:add_publicite.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editPubliciteAction($isPopup, Request $request)
    {
        $pub = $this->getObjectRepoFrom(self::ENTITY_PUB, array('isPopup' => $isPopup));
        if(!$pub){
            $pub = new Publicite();
        }
        $form = $this->formPost(self::FORM_PUB, $pub);
        $form->handleRequest($request);

        if($form->isValid()){
            $file = $pub->getCheminPub();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->get('kernel')->getRootDir().'/../web/upload/admin/publicite/',
                $fileName
            );
            // instead of its contents
            $pub->setCheminPub($fileName);
            if($isPopup){
                $pub->setIsPopup(true);
            }else{
                $pub->setIsPopup(false);
            }
            $this->insert($pub, array(
                'success' => $this->translate('success.pub.edit'),
                'error' => $this->translate('error.pub.edit')
            ));
            return $this->redirectToRoute('add_admin_mention');
        }
        return $this->render('BackAdminBundle:Publicite:edit_publicite.html.twig', array(
            'form' => $form->createView(),
            'publicite' => $pub
        ));
    }

    public function removePubliciteAction($id)
    {
        $pub = $this->getRepoFormId(self::ENTITY_PUB, $id);
        $this->remove($pub);
        return $this->redirectToRoute('index_publicite');
    }

}
