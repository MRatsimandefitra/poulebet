<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Mention;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MentionController extends ApiController
{
    const FORM_MENTION = 'Api\DBBundle\Form\MentionType';
    const ENTITY_MENTION = 'ApiDBBundle:Mention';


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
}
