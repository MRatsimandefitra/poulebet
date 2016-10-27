<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PaysRegionController extends ApiController
{
    const ENTITY_MENTION = 'ApiDBBundle:Mention';


    /**
     * @ApiDoc(
     *      description="Recuperer tous les pays et les régions"
     * )
     * @return JsonResponse
     */
    public function getAllAction(Request $request){

        $pays = $this->getEm()->getRepository('ApiDBBundle:Pays')->findAllOrdered();
        $output = array(
            'success'     => true,
            'code_error'  => 0,
            'list_pays'   => array(),
            'list_region' => array()
        );
        if(!empty($pays)){
            foreach($pays as $value){
                $output['list_pays'][] = array(
                    'id'  => $value->getId(),
                    'nom' => $value->getNomPays()
                );
            }
            foreach($this->getEm()->getRepository('ApiDBBundle:Region')->findAllOrdered() as $region){
                $output['list_region'][] = array(
                    'id'      => $region->getId(),
                    'nom'     => $region->getNom(),
                    'id_pays' => $region->getPays()->getId() 
                );
            }
        } else {
            $output['success'] = false;
            $output['code_error'] = 0;
        }
        return new JsonResponse($output);
    }
}
