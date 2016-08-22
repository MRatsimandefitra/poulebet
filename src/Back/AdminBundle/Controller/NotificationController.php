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
/**
 * Description of PushNotificationController
 *
 * @author miora.manitra
 */

class NotificationController extends ApiController {
    
    const ENTITY_DEVICE = 'ApiDBBundle:Device';
    const FORM_NOTIFICATION = 'Api\DBBundle\Form\NotificationType';
    
    public function notifyAction(Request $request){
        
        $notification = new Notification();
        $form = $this->formPost(self::FORM_NOTIFICATION, $notification);
        $form->handleRequest($request);      
        //echo("<pre>");
        //var_dump($notification->getUtilisateurs());
        if ($form->isValid()) {
            //insertion dans la base de donnée
            $this->insert($notification);
            $users = $notification->getUtilisateurs();
            $device_token = array();
            $message = $request->get("message");
            $messageData = array("message"=>$message);
            foreach($users as $user){
                $devices = $user->getDevices();
                foreach ($devices as $device){
                    $device_token[] = $device->getToken();
                } 
            }
            $data = array(
                'registration_ids',$device_token,
                'data',$messageData
            );
            $this->sendGCMNotification($data);
            return $this->redirectToRoute('add_notification');
            
        }
        return $this->render('BackAdminBundle:Notification:index.html.twig', array(
            'form' => $form->createView(),
        ));
        
       
        
    }
}
