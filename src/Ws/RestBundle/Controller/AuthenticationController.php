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
                $pass_result = $this->encodePassword($password);
                if($user['password'] == $pass_result){
                    $token = $this->generateToken($user['userToken']);
                    return new JsonResponse(array(
                        'token'=>$token,
                        'infos_users' => $user,
                        'success' => true,
                        'sessid'=>$res->sessid,
                        'session_name'=>$res->session_name,
                        'appyone_Nid'=>$pari_sport->Nid,
                        'icone_design_general'=>$pari_sport->icone_design_general,
                        'appyone_token'=>$res->token,
                        'appyone_details'=>$res2  
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
