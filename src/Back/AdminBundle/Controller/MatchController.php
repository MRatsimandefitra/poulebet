<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Championat;
use Api\DBBundle\Entity\Match;
use Api\DBBundle\Entity\matchIndividuel;
use Api\DBBundle\Entity\Matchs;
use Back\AdminBundle\Resources\Request\MatchSearch;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class MatchController extends ApiController
{
    const FORM_MATCH_SEARCH = 'Api\DBBundle\Form\MatchSearchType';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';

    public function indexAction(Request $request)
    {
        if($request->request->get('identifiant')){
            $identifiant = $request->request->get('identifiant');
            $cote1 = 'cote1_'.$identifiant;
            $coten = 'coten_'.$identifiant;
            $cote2 = 'cote2_'.$identifiant;
            if($request->request->get($cote1)){
                $rCote1 = $request->request->get($cote1);
            }

            if($request->request->get($coten)){
                $rCoten = $request->request->get($coten);
            }

            if($request->request->get($cote2)){
                $rCote2 = $request->request->get($cote2);
            }

            $cHost = 'c_host_'.$identifiant;
            if($request->request->get($cHost)){
                $rHost = $request->request->get($cHost);
            }

            $cNeutre = 'c_neutre_'.$identifiant;
            if($request->request->get($cNeutre)){
                $rNeutre = $request->request->get($cNeutre);
            }
            $cGuest = 'c_guest_'.$identifiant;
            if($request->request->get($cGuest)){
                $rGuest = $request->request->get($cGuest);
            }

            $match = $this->getRepoFormId(self::ENTITY_MATCH, $identifiant);
            $match->setCot1Pronostic($rCote1);
            $match->setCoteNPronistic($rCoten);
            $match->setCote2Pronostic($rCote2);
            $this->get('doctrine.orm.entity_manager')->persist($match);
            $this->get('doctrine.orm.entity_manager')->flush();
        }

        if($request->get('date_match')){
            $dateMatch = $request->get('date_match');
        }
        if($request->get('time_match')){
            $heureMatch = $request->get('time_match');
        }
        if($request->get('championat_match')){
            $championat = $request->get('championat_match');
        }
        if($request->get('pays_match')){
            $pays= $request->get('pays_match');
        }


        $matchs = $this->getAllEntity(self::ENTITY_MATCH);
        $championatData = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $country = $this->getAllEntity(self::ENTITY_COUNTRY);

        return $this->render('BackAdminBundle:Matchs:index.html.twig', array(
            'matchs' => $matchs,
            'championat' => $championatData,
            'country' => $country,
            /*'items' => $items,
            'totalMatch' => $totalMatch,
            'search' => $dateMatchSearch,
            'dateSearch' => $dateMatchSearch,
            'timeSearch' => $heureMatchSearch,
            'country' => $country,
            'championat' => $championatData*/
            /*'form' => $form->createView()*/
        ));
    }

    public function insertMatchAction(Request $request){

        return $this->render('BackAdminBundle:Matchs:index.html.twig', array(
       /*     'matchs' => $matchs,
            'championat' => $championatData,
            'country' => $country,*/
            /*'items' => $items,
            'totalMatch' => $totalMatch,
            'search' => $dateMatchSearch,
            'dateSearch' => $dateMatchSearch,
            'timeSearch' => $heureMatchSearch,
            'country' => $country,
            'championat' => $championatData*/
            /*'form' => $form->createView()*/
        ));
    }
}
