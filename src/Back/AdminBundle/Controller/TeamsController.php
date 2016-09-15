<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TeamsController extends ApiController
{
    const ENTITY_TEAMS= 'ApiDBBundle:Teams';

    public function listTeamsAction(Request $request)
    {
        if($request->get('logo') == 0){
            $data = $this->getAllEntity(self::ENTITY_TEAMS);
            foreach($data as $k => $v){

                $urlImg = $this->get('kernel')->getRootDir().'/../web/images/Flag-foot/'.$v->getIdNameClub().".png";
                if(!file_exists($urlImg)){
                    $dataResult[] = array(
                        'id' => $v->getId(),
                        'nomClub' => $v->getNomClub(),
                        'idNameClub' => $v->getIdNameClub(),
                        'fullNameClub' => $v->getFullNameClub(),
                        'codePays' => $v->getCodePays(),
                        'logo' => $v->getLogo()
                    );
                }
            }

        }

        if(!$request->get('logo')){
            $dataResult = $this->getAllEntity(self::ENTITY_TEAMS);
            if(!$dataResult){
                throw new \Exception('waiaka');
            }
        }
        if($request->get('logo') == 1 ){
            $data  = $this->getAllEntity(self::ENTITY_TEAMS);
            if($data){
                foreach($data as $k => $v ){
                    $urlImg = $this->get('kernel')->getRootDir().'/../web/images/Flag-foot/'.$v->getIdNameClub().".png";
                    if(file_exists($urlImg)){
                        $dataResult[] = array(
                            'id' => $v->getId(),
                            'nomClub' => $v->getNomClub(),
                            'idNameClub' => $v->getIdNameClub(),
                            'fullNameClub' => $v->getFullNameClub(),
                            'codePays' => $v->getCodePays(),
                            'logo' => $v->getLogo()
                        );
                    }
                }
            }

        }

        return $this->render('BackAdminBundle:Teams:list_teams.html.twig', array(
            'data' => $dataResult
        ));
    }

    public function exportTeamsAction(Request $request)
    {

        $name = $this->get('kernel')->getRootDir().'/../web/csv/teams.csv';
        if($request->get('logo') == 0){
            $data = $this->getAllEntity(self::ENTITY_TEAMS);
            foreach($data as $k => $v){

                $urlImg = $this->get('kernel')->getRootDir().'/../web/images/Flag-foot/'.$v->getIdNameClub().".png";
                if(!file_exists($urlImg)){
                    $dataResult[] = array(
                        'id' => $v->getId(),
                        'nomClub' => $v->getNomClub(),
                        'idNameClub' => $v->getIdNameClub(),
                        'fullNameClub' => $v->getFullNameClub(),
                        'codePays' => $v->getCodePays(),
                        'logo' => $v->getLogo()
                    );
                }
            }
        }
        $handle   = fopen($name, 'w');
        fputcsv($handle, array('Logo', 'ID Name club', 'Nom Club', 'Nom comlet', 'Code pays'));
        foreach($dataResult as $value){
            fputcsv($handle, array($value['logo'], $value['idNameClub'], $value['nomClub'], $value['fullNameClub'], $value['codePays']));
        }
        fclose($handle);
        return new Response(
            file_get_contents($name),
            200,
            array(
                'Content-Type'        => 'text/csv; charset=utf-8',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $n = 'teams_sans_logo.xls')
            )
        );

        /* if($request->get('logo') == 0){
         $data = $this->getAllEntity(self::ENTITY_TEAMS);
         foreach($data as $k => $v){

                 $urlImg = $this->get('kernel')->getRootDir().'/../web/images/Flag-foot/'.$v->getIdNameClub().".png";
                 if(!file_exists($urlImg)){
                     $dataResult[] = array(
                         'id' => $v->getId(),
                         'nomClub' => $v->getNomClub(),
                         'idNameClub' => $v->getIdNameClub(),
                         'fullNameClub' => $v->getFullNameClub(),
                         'codePays' => $v->getCodePays(),
                         'logo' => $v->getLogo()
                     );
                 }
             }
         }*/
        /*return $this->render('BackAdminBundle:Teams:export_teams.html.twig', array(
            // ...
        ));*/
    }

}
