<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class PronosticController extends ApiController
{
    const ENTITY_MATCH = 'ApiDBBundle:Matchs';
    const ENTITY_COUNTRY = 'ApiDBBundle:Country';
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';

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
        $where[] = " m.masterProno1 IS NOT NULL OR m.masterPronoN IS NOT NULL OR m.masterProno2 IS NOT NULL";
        if (!empty($where)) {
            $dql .= ' WHERE ' . implode(' AND ', $where);
        }
        $matchs = $this->get('doctrine.orm.entity_manager')->createQuery($dql)->setParameters($params)->getResult();
        $droitAdmin = $this->getDroitAdmin('Master Pronostic');

        return $this->render('BackAdminBundle:Pronostic:index.html.twig', array(
            'matchs' => $matchs,
            'country' => $coutry,
            'championat' => $championat,
            'droitAdmin' => $droitAdmin[0]
            /*'items' => $items,
            'totalMatch' => $totalMatch,
            'search' => $dateMatchSearch*/
        ));
    }

    public function removePronosticAction($id){
        $entity = $this->getRepoFormId(self::ENTITY_MATCH, $id);
        try{

            $entity->setMasterProno1(null);

            $entity->setMasterPronoN(null);
            $entity->setMasterProno2(null);
            $entity->setCot1Pronostic(null);
            $entity->setCoteNPronistic(null);
            $entity->setCote2Pronostic(null);
            $this->get('doctrine.orm.entity_manager')->flush();

            return $this->redirectToRoute('index_admin_pronostic');

        }catch(Exception $e){
            return false;
        }


    }

    private function getDroitAdmin($droit){
        $droitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findBy(array('admin' => $this->getUser(), 'droit' => $this->getRepo(self::ENTITY_DROIT)->findOneBy(array('fonctionnalite' => $droit))));
        return $droitAdmin;
    }
}
