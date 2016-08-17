<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Ws\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
/**
 * Description of AppyOneController
 *
 * @author miora.manitra
 */
class AppyOneController extends ApiRestController{
    //put your code here
    public function getDataAction(){
        // traitement WS  appyOne
        // chargement des paramètres
        $appyone_url_login = $this->getParameter("appyone_url_login");
        $appyone_username = $this->getParameter("appyone_username");
        $appyone_password = $this->getParameter("appyone_password");
        $appyone_url_liste_application = $this->getParameter("appyone_url_liste_application");
        $nid = $this->getParameter("appyone_nid");
        $appyone_url_details = $this->getParameter("appyone_url_details");

        // login appyOne
        $data = array("username"=>$appyone_username, "password"=>$appyone_password);
        $dataJson = json_encode($data);
        $http = $this->get('http');
        $http->setUrl($appyone_url_login);
        $http->setRawPostData($dataJson);
        $resultat = $http->execute();
        $res = json_decode($resultat);

        //die($resultat['sessid']);
        // liste application appyOne
        $http->setUrl($appyone_url_liste_application);
        $http->setHeaders("Cookie:".$res->session_name."=".$res->sessid);
        $http->setHeaders("X-CSRF-Token:".$res->token);
        $resultat = $http->execute();
        $res1 = json_decode($resultat);
        $pari_sport = null;
        foreach ($res1->nodes as $node){
            $noeud = $node->node;
            if ($noeud->Nid == $nid){
                $pari_sport = $noeud;
                break;
            }
        }
        $appyone_url_details = str_replace("{id}",$pari_sport->Nid,$appyone_url_details);
        $http->setUrl($appyone_url_details);
        $http->doGet();
        $res2 = $http->execute();
        //var_dump($res2);die();
        $res2 = json_decode($res2);
                  
        return new JsonResponse(array(
            'success'=>true,
            'sessid'=> $res->sessid,
            'session_name'=> $res->session_name,
            'appyone_Nid'=> $pari_sport->Nid,
            'icone_design_general'=> $pari_sport->icone_design_general,
            'appyone_token'=>$res->token,
            'appyone_details'=>$res2 
        ));
    }
}
