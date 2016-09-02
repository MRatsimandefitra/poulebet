<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class MentionController extends ApiController
{
    const ENTITY_MENTION = 'ApiDBBundle:Mention';


    public function getAllMentionAction(){

        $mention = $this->getAllEntity(self::ENTITY_MENTION, array());
        if(!$mention){
            return new JsonResponse(array(
                'code_error' => 4,
                'success' => false,
                'message' => 'Aucune mention n\'est disponible dans poulebet.'
            ));
        }
        $result = array(
            'mentionLegale' => $mention[0]->getId(),
            'cgv' => $mention[0]->getCgv(),
            'cgps' => $mention[0]->getCgps(),
            'cgu' => $mention[0]->getCgu()
        );
        if(!empty($result)){
            $result['success'] = true;
            $result['code_error'] = 0;
        }
        return new JsonResponse($result);
    }

    /**
     * This method is used to get all of mention in poulebet
     * You can have Mention Legales, CGV, CGPS, CGU data
     *
     * @ApiDoc(
     *  description="Description of all mentions in poulebet",
     * )
     */
    public function getMentionLegale(){
        $mention = $this->getMentionData();
        $result = array(
            'mentionLegale' => $mention[0]->getId(),

        );
        if(!empty($result)){
            $result['success'] = true;
            $result['code_error'] = 0;
        }
        return new JsonResponse($result);
    }

    public function getMentionCGUAction(){
        $mention = $this->getMentionData();
        $result = array(
            'cgu' => $mention[0]->getCgu()

        );
        if(!empty($result)){
            $result['success'] = true;
            $result['code_error'] = 0;
        }
        return new JsonResponse($result);
    }

    public function getMentionCGPSAction(){
        $mention = $this->getMentionData();
        $result = array(
            'cgps' => $mention[0]->getCgps(),
        );
        if(!empty($result)){
            $result['success'] = true;
            $result['code_error'] = 0;
        }
        return new JsonResponse($result);
    }

    public function getMentionCGVAction(){
        $mention = $this->getMentionData();
        $result = array(
            'cgv' => $mention[0]->getCgv()
        );
        if(!empty($result)){
            $result['success'] = true;
            $result['code_error'] = 0;
        }
        return new JsonResponse($result);
    }

    private function getMentionData(){
        $mention = $this->getAllEntity(self::ENTITY_MENTION, array());
        if(!$mention){
            return new JsonResponse(array(
                'code_error' => 4,
                'success' => false,
                'message' => 'Aucune mention n\'est disponible dans poulebet.'
            ));
        }
        return $mention;
    }

}
