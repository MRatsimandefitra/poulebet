<?php

namespace Ws\RestBundle\Controller;

use Api\DBBundle\Entity\Utilisateur;
use Api\DBBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class AuthenticationController extends ApiRestController{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    const ENTITY_DEVICE = 'ApiDBBundle:Device';
    
    
    public function authenticationAction(Request $request){
        if($request->get('email') && $request->get('password')){
            $email = $request->get('email');
            $password = $request->get('password');
            $user = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->findByEmailArray($email);
            $userEntity = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->findByEmail($email);
            // récupération de token google cloud message du device
            $gcm_device_token=$request->get("gcm_device_token");
            $device = $this->getEm()->getRepository(self::ENTITY_DEVICE)->findByToken($gcm_device_token);
            if($user){
                if(!$device || $device->getUtilisateur()->getId() != $user['id']){
                    $device = new Device();
                    $device->setToken($gcm_device_token);
                    $userEntity = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->find($user['id']);
                    $device->setUtilisateur($userEntity);
                    $this->insert($device);
                }
                $pass_result = $this->encodePassword($password);
                if($user['password'] == $pass_result){
                    $token = $this->generateToken($user['userToken']);
                    return new JsonResponse(array(
                        'token'=>$token,
                        'infos_users' => $user,
                        'success' => true, 
                    ));
                }
                else{
                    return new JsonResponse(
                        array(
                            'success' => false,
                            'message' => 'Mot de passe incorrect'
                        )
                    );
                }
            }
            else {
                return new JsonResponse(
                        array(
                            'success' => false,
                            'message' => "email n'existe pas"
                        )
                );
            }
        }
        return new JsonResponse(
            array(
                'success' => false,
                'message' => "champs non renseigné"
            )
        );
               
    }
    public function forgottenPasswordAction(Request $request){
        $email = $request->get('email');
        $res = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->findByEmail($email);
        if($res){
            $user = $res[0];
            
            $parameter = $this->getParameterMail();
                $pass = $this->generatePassword();
                $email = $request->get('email');
                $prenom = $user->getPrenom();
                $body = $parameter->getTemplateMdpOublie();
                $mailerService = $this->getMailerService();
                
                $res = str_replace('{{prenom}}',$prenom,$body);
                $res = str_replace('{{email}}',$email ,$res);
                $res = str_replace('{{password}}',$pass,$res);
                $res = str_replace('{{adresseSociete}}', $parameter->getAdresseSociete(), $res);
                
                $mailerService->setSubject($parameter->getSubjectMdpOublie());
                $mailerService->setFrom($parameter->getEmailSite());
                $mailerService->setTo($email);
                $mailerService->addParams('body',$res);
                                            
                if($mailerService->send()){
                    $user->setPassword($this->encodePassword($pass));
                    $this->getEm()->persist($user);
                    $this->getEm()->flush();
                    return new JsonResponse(
                        array(
                            'success' => true,
                            'message' => "Votre mot de passe est envoyé par email"
                        )
                    );
                }
            
        }
        return new JsonResponse(
                array(
                    'success' => false,
                    'message' => "Désolé, nous ne reconnaissons pas cette adresse mail"
                )
            );
    }
}
