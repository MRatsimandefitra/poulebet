<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Championat;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Intl\Intl;
use Symfony\Component\Intl\Locale;
use Symfony\Component\HttpFoundation\Session\Session;

class ChampionnatController extends ApiController
{
    const ENTITY_CHAMPIONAT = 'ApiDBBundle:Championat';
    const FORM_CHAMPIONAT = 'Api\DBBundle\Form\ChampionatType';


    public function listChampionatAction(Request $request){
        $session = new Session();
        
        $session->set("current_page","Championnat");
        $championat = $this->getAllEntity(self::ENTITY_CHAMPIONAT);
        $droitAdmin = $this->getRolesAdmin()->getDroitAdmin('Matchs');
        return $this->render('BackAdminBundle:Championnat:list_championat.html.twig', array(
            'entities' => $championat,
            'droitAdmin' => $droitAdmin[0]
        ));
    }




    public function editChampionatAction(Request $request, $id){
        $championat = $this->getRepoFormId(self::ENTITY_CHAMPIONAT, $id);

        $form = $this->formPost(self::FORM_CHAMPIONAT, $championat);
        $form->handleRequest($request);
        /*$countries = $request->get("pays");
        $championat->setPays($countries);*/
        if($form->isValid()){
            $this->insert($championat, array('success' => 'success' , 'error' => 'error'));
            return $this->redirectToRoute('list_championat');
        }
        $droitAdmin = $this->getRolesAdmin()->getDroitAdmin('Matchs');
        return $this->render('@BackAdmin/Championnat/edit_championat.html.twig', array(
            'form' => $form->createView(),
            'championat' => $championat,
            'droitAdmin' => $droitAdmin[0]
        ));
    }

    public function addChampionatAction(Request $request){

        $championat = new Championat();

        $form = $this->formPost(self::FORM_CHAMPIONAT, $championat);
        $form->handleRequest($request);
        /*$dateDebut = $form['dateDebutChampionat']->getData();
        $dateD = new \Date($dateDebut);
        $dateFinale = $form['dateFinaleChampionat']->getData();
        $dateF = new \DateTime($dateDebut);
        $championat->setDateDebutChampionat($dateD);
        $championat->setDateFinaleChampionat($dateF);*/
        //$countries = Intl::getRegionBundle()->getCountryName(strtoupper($form['pays']->getData()));
        //$countries = strtoupper($form['pays']->getData());
        //$championat->setPays($countries);

        if($form->isValid()){
            $this->insert($championat, array('success' => 'success' , 'error' => 'error'));
            return $this->redirectToRoute('list_championat');
        }
        $droitAdmin = $this->getRolesAdmin()->getDroitAdmin('Matchs');
        return $this->render('@BackAdmin/Championnat/add_championat.html.twig', array(
            'form' => $form->createView(),
            'championat' => $championat,
            'droitAdmin' => $droitAdmin[0]
        ));
    }

    public function removeChampionatAction($id){
        $entity = $this->getRepoFormId(self::ENTITY_CHAMPIONAT, $id);
        $this->remove($entity);
        return $this->redirectToRoute('list_championat');
    }
    private function getRolesAdmin(){
        $wsDA = $this->get('roles.manager');
        return $wsDA;
    }
}
