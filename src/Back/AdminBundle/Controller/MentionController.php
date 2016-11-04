<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\CommonBundle\Fixed\InterfaceDB;
use Api\DBBundle\Entity\ApiKey;
use Api\DBBundle\Entity\Facebook;
use Api\DBBundle\Entity\Mention;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class MentionController extends ApiController implements InterfaceDB
{


    public function addMentionAction(Request $request){

        $session = new Session();
        
        $session->set("current_page","Mention");
        $mention = $this->getObjectRepoFrom(self::ENTITY_MENTION, array());
        if(!$mention){
            $mention = new Mention();
        }
        $form = $this->formPost(self::FORM_MENTION, $mention);
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($mention, array('success' => 'success' , 'error' => 'error'));

        }
        return $this->render('BackAdminBundle:Mention:add_mention.html.twig', array(
                'form' => $form->createView(),

        ));
    }


    public function editApiKeyAction(Request $request){
        $apikey = $this->getAllRepo(self::ENTITY_APIKEY);
        if(!$apikey){
            $apikey = new ApiKey();
        }
        if($apikey){
            foreach($apikey as $vApikey){
                $apikey = $vApikey;
            }
        }
        $form = $this->formPost(self::FORM_APIKEY, $apikey );
        $form->handleRequest($request);
        if($form->isValid()){
            $this->insert($apikey, array('success' => 'success', 'error' => 'error'));
        }
        return $this->render('BackAdminBundle:Mention:edit_apikey.html.twig', array(
            'form' => $form->createView(),

        ));
    }

    public function editFacebookAction(Request $request){
        $facebook = $this->getAllEntity(self::ENTITY_FACEBOOK);
        $newUpload = true;
        if(is_array($facebook) && count($facebook) > 0){
            $newUpload = false;
        }
        if($newUpload){
            $facebook =  new Facebook();
        }else{
            $facebook = $facebook[0];
        }

        $form = $this->formPost(self::FORM_FACEBOOK, $facebook);
        $form->handleRequest($request);
        if($form->isValid()){
            // oeuf
            if($newUpload){
                $imgOeuf = $facebook->getImageOeuf();
            }else{

                $imgOeuf = $form['imageOeuf']->getData();

            }

            $nameImageOeuf = md5(uniqid()).'.'.$imgOeuf->guessExtension();
            $imgOeuf->move(
                $this->get('kernel')->getRootDir().'/../web/upload/admin/facebook/',
                $nameImageOeuf
            );
            $facebook->setImageOeuf($nameImageOeuf);


            // poulebet
            if($newUpload){
                $imgPoulebet = $facebook->getImagePoulebet();
            }else{
                $imgPoulebet = $form['imagePoulebet']->getData();
            }

            $nameImagePoulebet = md5(uniqid()).'.'.$imgPoulebet->guessExtension();
            $imgPoulebet->move(
                $this->get('kernel')->getRootDir().'/../web/upload/admin/facebook/',
                $nameImagePoulebet
            );

            $facebook->setImagePoulebet($nameImagePoulebet);

            if($newUpload){
                $this->get('doctrine.orm.entity_manager')->persist($facebook);
            }

            $this->get('doctrine.orm.entity_manager')->flush();
            return $this->redirectToRoute('add_admin_mention');
        }
        return $this->render('BackAdminBundle:Mention:edit_facebook.html.twig', array(
            'form' => $form->createView(),
            'facebook' => $facebook
        ));
    }
}
