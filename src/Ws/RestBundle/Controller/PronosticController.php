<?php

namespace Ws\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class PronosticController extends ApiRestController
{

    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_CHAMPIONNAT = 'ApiDBBundle:Championnat';
    
    public function getUtilisateurAchatPromoAction()
    {

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!$user) {
            return new JsonResponse(array(
                'code' => 'NOK',
                'isConnected' => false
            ));
        }
        $currentUseur = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->findOneBy(array('id' => $user->getId()));
        if ($currentUseur) {
            $result = array();
            $result['achatPromo'] = $currentUseur->getAchatProno();
            $result['code'] = 'OK';
            $result['isConnected'] = true;
            return new JsonResponse($result);
        }
    }
    /**
     * récupérer les championnats qui on des matchs
     */
    public function getChampionnatAction(){
        
    }


}
