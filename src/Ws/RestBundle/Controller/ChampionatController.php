<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChampionatController extends ApiController
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';

    public function getChampionatWithMatchAction(){
        $matchsWithChampionat = $this->getRepo(self::ENTITY_MATCHS)->findChampionatWithMatch();
        $result = array();

        foreach($matchsWithChampionat as $vData){
            $result[] = array(
                'id' => $vData->getId(),
                'dateMatch' => $vData->getDateMatch(),
                'equipeDomicile' => $vData->getEquipeDomicile(),
                'equipeVisiteur' => $vData->getEquipeVisiteur(),
                'cheminLogoDomicile' => $this->get('kernel')->getRootDir().'/../web/upload/admin/flag/' .$vData->getCheminLogoDomicile(),
                'cheminLogoVisiteur' => $this->get('kernel')->getRootDir().'/../web/upload/admin/flag/' .$vData->getCheminLogoDomicile(),
                'score' => $vData->getScore(),
                'resultatDomicile' => $vData->getResultatDomicile(),
                'resultatVisiteur' => $vData->getResultatVisiteur(),
                'cotePronostic1' => $vData->getCot1Pronostic(),
                'cotePronostic2' => $vData->getCote2Pronostic(),
                'cotePronosticN' => $vData->getCoteNPronistic(),
                'masterProno1' => $vData->getMasterProno1(),
                'masterProno2' => $vData->getMasterProno2(),
                'masterPronoN' => $vData->getMasterPronoN(),
                'tempsEcoules' => $vData->getTempsEcoules()

            );
        }
        if(!empty($result)){
            $result['code_error'] = 0;
            $result['message'] = "get Data réussi avec success";
        }else{
            $result['code_error'] = 2;
            $result['message'] = "Aucune donné n'a été trouvé";
        }
        return  new JsonResponse($result);
    }

    public function getChampionatWithNationalMatch(){
        $this->getRepo(self::ENTITY_CHAMPIONAT);
    }
}
