<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\ApiKey;
use Api\DBBundle\Entity\Mention;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MentionController extends ApiController
{
    const FORM_MENTION = 'Api\DBBundle\Form\MentionType';
    const ENTITY_MENTION = 'ApiDBBundle:Mention';
    const ENTITY_APIKEY = 'ApiDBBundle:ApiKey';
    const FORM_APIKEY = 'Api\DBBundle\Form\ApiKeyType';

    public function addMentionAction(Request $request){

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
}
