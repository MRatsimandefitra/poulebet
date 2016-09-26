<?php

namespace Ws\RestBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class PronosticController extends ApiController
{

    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_CHAMPIONNAT = 'ApiDBBundle:Championat';
    const ENTITY_MATCHS = 'ApiDBBundle:Matchs';

    /**
     * @ApiDoc(
     *      description = "Recuperr les utilisateur achat promo"
     * )
     * @return JsonResponse
     */
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
     * :Récupérer une liste des matchs pronostiqués par championnat
     * @ApiDoc(
     *      description=":Récupérer une liste des matchs pronostiqués ",
     *      parameters = {
     *          {"name" = "date", "dataType"="date" ,"required"=false, "description"= "date of matchs lets mec ...."},
     *          {"name" = "championat", "dataType"="string" ,"required"=false, "description"= "championat."}
     *      }
     * )
     */
    public function getMatchPronosticByParameterAction(Request $request){
           $championnat = $request->request->get('championat');
        $date = $request->request->get('date');

        $maths = $this->getRepo(self::ENTITY_MATCHS)->findMatchPronosticByParameter($championnat, $date);
        $championnat = $this->getRepo(self::ENTITY_MATCHS)->findMatchPronosticByParameter($championnat, $date, true);

        $result = array();
        $result['nb_championat'] = count($championnat);
        $result['nb_matchs'] = count($maths);
        $result['date_request']  = date('Y-m-d H:i');
        if($championnat){
            foreach($championnat as $kChampionat => $itemsChampionat){
                $result['list_championat'][] = array(
                    'idChampionat' => $itemsChampionat->getChampionat()->getId(),
                    'nomChampionat' => $itemsChampionat->getChampionat()->getNomChampionat(),
                    'fullNameChampionat' => $itemsChampionat->getChampionat()->getFullNameChampionat()
                );
            }
        }
        if($maths){
            if(is_array($maths)){
                foreach($maths as $kMatchs => $itemsMatchs){

                        $result['list_match'][] = array(
                            'id' => $itemsMatchs->getId(),
                            'dateMatch' => $itemsMatchs->getDateMatch(),
                            'equipeDomicile' => $itemsMatchs->getEquipeDomicile(),
                            'equipeVisiteur' => $itemsMatchs->getEquipeVisiteur(),
                            'logoDomicile' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchs->getCheminLogoDomicile() . '.png',
                            'logoVisiteur' => 'dplb.arkeup.com/images/Flag-foot/' . $itemsMatchs->getCheminLogoDomicile() . '.png',
                            'score' => $itemsMatchs->getScore(),
                            'status' => $itemsMatchs->getStatusMatch(),
                            'cote_pronostic_1' => $itemsMatchs->getCot1Pronostic(),
                            'cote_pronostic_n' => $itemsMatchs->getCoteNPronistic(),
                            'cote_pronostic_2' => $itemsMatchs->getCote2Pronostic(),
                            'master_prono_1' => $itemsMatchs->getMasterProno1(),
                            'master_prono_n' => $itemsMatchs->getMasterPronoN(),
                            'master_prono_2' => $itemsMatchs->getMasterProno2(),
                            'tempsEcoules' => $itemsMatchs->getTempsEcoules(),
                            'idChampionat' => $itemsMatchs->getChampionat()->getId()

                        );
                }
                $result['code_error'] = 0;
                $result['error'] = false;
                $result['success'] = true;

            }
        }else{
            $result['code_error'] = 4;
            $result['error'] = false;
            $result['success'] = true;
            $result['message'] = "Aucun résultat trouvé";

        }
        return new JsonResponse($result);
    }



}


