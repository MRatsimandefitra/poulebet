<?php

namespace Ws\RestBundle\Controller;

use Api\DBBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class AuthenticationController extends ApiRestController{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';
    
    public function authenticationAction(Request $request){
        if($request->get('email') && $request->get('password')){
            $email = $request->get('email');
            $password = $request->get('password');
            $user = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->findByEmailArray($email);
            if($user){
                $pass_result = $this->encodePassword($password);
                if($user['password'] == $pass_result){
                    $token = $this->generateToken($user['userToken']);
                    return new JsonResponse(array(
                        'token'=>$token,
                        'infos_users' => $user,
                        'success' => true
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
            $pass = $this->generatePassword();
            $message = \Swift_Message::newInstance()
                    ->setSubject('Mot de passe oublié')
                    ->setFrom('test@yahoo.com')
                    ->setTo($email)
                    ->setBody(
                            $this->renderView(
                                'Email/forgotten_password.html.twig',
                                array('password' => $pass)
                            ),
                            'text/html');
            if($this->sendEmail($message)){
                $user->setPassword($this->encodePassword($pass));
                $this->getEm()->persist($user);
                $this->getEm()->flush();
                return new JsonResponse(
                    array(
                        'success' => true,
                        'message' => "Mdp envoyé par email"
                    )
                );
            }
            
        }
        return new JsonResponse(
                array(
                    'success' => false,
                    'message' => "adresse email non reconnu"
                )
            );
    }
}
