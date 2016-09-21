<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Symfony\Component\HttpFoundation\Request;
use Api\DBBundle\Entity\Notification;
use Symfony\Component\HttpFoundation\Session\Session;
/**
 * Description of PushNotificationController
 *
 * @author miora.manitra
 */

class NotificationController extends ApiController {
    
    const ENTITY_DEVICE = 'ApiDBBundle:Device';
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const FORM_NOTIFICATION = 'Api\DBBundle\Form\NotificationType';
    
    public function notifyAction(Request $request){
        // utilistateurs
        $session = new Session();
        
        $session->set("current_page","Notification");
        
        $tri = $request->get('tri');
        $champ = $request->get('champ');
        $users_id = $request->get('users');
        $all_user = $request->get("all_user");
        
        $nbpage = 10; 
        $criteria = array("criteria_username"=>null,"criteria_email"=>null);
        if($request->get('nbpage')){
            $nbpage = $request->get('nbpage');
        }
        if($request->get('criteria_username')!=null){
            $criteria["criteria_username"]= $request->get('criteria_username');
        }
        if($request->get('criteria_email')){
            $criteria["criteria_email"]= $request->get('criteria_email');
        }
        if($request->get('criteria_score_total')){
            $criteria["criteria_score_total"]= $request->get('criteria_score_total');
        }
        $utilisateur = $this->getRepo(self::ENTITY_UTILISATEUR)->getAllUtilisateurs($champ, $tri,$criteria);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $utilisateur, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $nbpage/*limit per page*/
        );
        
        $notification = new Notification();
        $form = $this->formPost(self::FORM_NOTIFICATION, $notification);
        $form->handleRequest($request);      
        //echo("<pre>");
        //var_dump($notification->getUtilisateurs());
        
        if ($form->isValid()) {
            $all=false;
            if($all_user){
                $all= true;
                $usrs = $this->getRepo(self::ENTITY_UTILISATEUR)->findAll();
                foreach($usrs as $item){
                    $notification->addUtilisateur($item);
                }
            }
            if($users_id){
                
                if(!$all){
                    foreach($users_id as $id){
                        $usr = $this->getRepo(self::ENTITY_UTILISATEUR)->find($id);
                        $notification->addUtilisateur($usr);
                    }
                }
                
            }
            //insertion dans la base de donnée
            $this->insert($notification);
            $users = $notification->getUtilisateurs();
            $device_token = array();
            $message = $notification->getMessage();
            $messageData = array("message"=>$message,"type"=>"Poulebet");
            foreach($users as $user){
                $devices = $user->getDevices();
                foreach ($devices as $device){
                    //$device_token[] = $device->getToken();
                    array_push($device_token, $device->getToken());
                } 
            }
            $data = array(
                'registration_ids' => $device_token,
                'data' => $messageData
            );
            $this->sendGCMNotification($data);
            return $this->redirectToRoute('add_notification');
            
        }
        return $this->render('BackAdminBundle:Notification:index.html.twig', array(
            'form' => $form->createView(),
            'pagination'=>$pagination,
            'criteria'=>$criteria,
            'all_user'=>$all_user
        ));
        
       
        
    }
}
