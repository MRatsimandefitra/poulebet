<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PronosticController extends ApiController
{
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    public function indexAction(Request $request)
    {
        //$matchs = $this->getAllEntity(self::ENTITY_MATCH);
        $coutry = $this->getAllEntity(self::ENTITY_COUNTRY);
        $championat = $this->getAllEntity(self::ENTITY_CHAMPIONAT);

        $where = array();
        $params = array();

        $dql ="SELECT m from ApiDBBundle:Matchs m ";

        if($request->get('date_master_prono')){
            $dateMasterProno = $request->get('date_master_prono');
            $where[] = " m.dateMatch BETWEEN :dateStart and :dateEnd ";
            $params['dateStart'] = $request->get('date_master_prono'). ' 00:00:00';
            $params['dateEnd'] = $request->get('date_master_prono'). ' 23:59:59';
        }

        if($request->get('pays_master_prono')){
            $paysMasterProno = $request->get('pays_master_prono');
            $where[] = " m.equipeDomicile or m.equipeVisiteur LIKE :pays ";
            $params['pays'] = $request->get('pays_master_prono');
        }
        if($request->get('championat_master_prono')){
            $championatMasterProno = $request->get('championat_master_prono');
            $dql .= " LEFT JOIN m.championat c";
            $where[] = ' c.nomChampionat = :championat';
            $params['championat'] = $request->get('championat_master_prono');
        }
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        return $this->render('BackAdminBundle:Pronostic:index.html.twig', array(
            'matchs' => $matchs,
            'country' => $coutry,
            'championat' => $championat
            /*'items' => $items,
            'totalMatch' => $totalMatch,
            'search' => $dateMatchSearch*/
        ));
    }
}
