<?php

namespace Ws\RestBundle\Controller;

use Api\DBBundle\Entity\Connected;
use Api\DBBundle\Entity\Utilisateur;
use Api\DBBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Validator\Constraints\DateTime;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


class InscriptionController extends ApiRestController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';

    const ENTITY_PARAMETER_MAIL = 'ApiDBBundle:ParameterMail';

    const ENTITY_DROIT_ADMIN = '';
    const ENTITY_DROIT = 'ApiDBBundle:';

    /**
     * @ApiDoc(
     *      description="Inscription utilisateur ws via android ",
     *      parameters = {
     *          {"name"="username", "dataType"="string", "required" = true, "description" = "data of username"},
     *          {"name"="prenom", "dataType"="string", "required" = true, "description" = "data of prenom"},
     *          {"name"="email", "dataType"="string", "required" = true, "description" = "Data of email"},
     *          {"name"="password", "dataType"="string", "required" = true, "description" = "data of password"},
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
     public function postUserFromAndroidAction(Request $request)
     {

         $username = $request->get('username');
         $prenom = $request->get('prenom');
         $email = $request->get('email');
         $password = $request->get('password');
         if(!$email || !$prenom || !$password){
             return new JsonResponse(array(
                 'success' => false,
                 'code_error' => 4,
                 'message' => "Veuillez remplir les champs requis"
             ));
         }
         $data = $this->get('doctrine.orm.default_entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->testIfUserExist($username, $email);
         if ($data) {
             return new JsonResponse(array(
                 'success' => false,
                 'code_erreur' => 2,
                 'message' => 'Cet E-mail existe déjà'
             ));
         }
         $utilisateur = new Utilisateur();

         if ($this->setDataInUtilisateur($utilisateur, $request)) {
             $user = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->findByEmailArray($email);
             $token = $this->generateToken($user['userToken']);
             $deviceToken = $request->get("gcm_device_token");
             if($deviceToken){
                 $device = new Device();
                 $device->setToken($deviceToken);
                 $device->setUtilisateur($utilisateur);
                 $this->insert($device);
             }

             $userObject = $this->getEm()->getRepository(self::ENTITY_UTILISATEUR)->findOneByEmail($utilisateur->getEmail());
             if($userObject){

                 // authentification
                 $tokenSession = new UsernamePasswordToken($userObject, $userObject->getPassword(), "main", $userObject->getRoles());
                 $this->get("security.token_storage")->setToken($tokenSession);
                 // Fire the login event
                 // Logging the user in above the way we do it doesn't do this automatically

                 $event = new InteractiveLoginEvent($request, $tokenSession);
                 $this->get("event_dispatcher")->dispatch("security.interactive_login", $event);
                 if($tokenSession){
                     $connected = $this->get('doctrine.orm.entity_manager')->getRepository('ApiDBBundle:Connected')->findOneBy(array('username' => $tokenSession->getUser()->getEmail()));
                     $newConnected = false;
                     if(!$connected){
                         $connected = new Connected();
                         $newConnected = true;
                     }
                     $connected->setTokenSession($this->get("security.token_storage")->getToken()->getCredentials());
                     $connected->setUsername($tokenSession->getUser()->getEmail());
                     $connected->setDevice($deviceToken);
                     if($newConnected){
                         $this->getEm()->persist($connected);

                     }
                     $this->getEm()->flush();
                 }
             }
             // mail
            $parameter = $this->getParameterMail();
            $pass = $this->generatePassword();
            $email = $request->get('email');
            $prenom = $request->get('prenom');
            $body = $parameter->getTemplateInscription();

            $mailerService = $this->getMailerService();
            $res = str_replace('{{prenom}}',$prenom,$body);
            $res = str_replace('{{email}}',$email ,$res);
            $res = str_replace('{{password}}',$pass,$res);
            $res = str_replace('{{adresseSociete}}', $parameter->getAdresseSociete(), $res);

            $mailerService->setSubject($parameter->getSubjectInscription());
            $mailerService->setFrom($parameter->getEmailSite());
            $mailerService->setTo($email);
            $mailerService->addParams('body',$res);
            $mailerService->send();
                
             /*$mm = $this->get('mail.manager');
             $mm->setSubject($this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_UTILISATEUR));*/

             return new JsonResponse(array(
                 'token' => $token,
                 'infos_users' => $user,
                 'code_erreur' => 0,
                 'success' => true,
                 'message' => "Vous êtes inscrit"
             ));
         }
         return new JsonResponse(array(
            'success' => true,
            'code_erreur' => 0,
            'message' => "OK"
        ));
     }


    /**
     * @ApiDoc(
     *      description= " Details profil utilisateur ws mobile")
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfilUtilisateurAction(Request $request)
    {

        $token = $request->get('token');
        if(!$token){
            return $this->noToken();
        }
        $data = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->findOneBy(array('userTokenAuth' => $token));
        if(!$data){
            return $this->noUser();
        }
        $response = array();
        if (is_array($data) && count($data) <= 0 && empty($data)) {
            return new JsonResponse(array(
                'code_error' => 4,
                'success' => true,
                'error' => false,
                'message' => 'Utilisateur inexistant'
            ));
        }
            $response['profil'][] = array(
                'photo' => $data->getCheminPhoto(),
                'nom' => $data->getNom(),
                'prenom' => $data->getPrenom(),
                'sexe' => $data->getSexe(),
                'telephone' => $data->getTelephone(),
                'fax' => $data->getFax(),
                'username' => $data->getUsername(),
                'email' => $data->getEmail(),
                'adresse1' => $data->getAdresse1(),
                'adresse2' => $data->getAdresse2(),
                'adresse3' => $data->getAdresse3(),
                'ville' => $data->getVille(),
                'pays' => $data->getPays(),
            );

        $response['code_error'] = 0;
        $response['success'] = true;
        $response['error'] = false;
        $response['message']= "Success";
        return new JsonResponse($response);
    }

    /**
     * @ApiDoc(
     *      description = " recevoir les informations ws du profil utilisateur",
     *      requirements = {
     *              {"name"="username", "dataType"="string", "required"=true, "description"="username de l'utilisateur"}
     *      }
     * )
     * @param Request $request
     * @return JsonResponse
     */
    public function postProfilUtilisateurAction(Request $request)
    {
        $username = $request->get('username');
        $utilisateur = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->findOneBy(array('username' => $username));
        if (!$utilisateur) {
            $result = array(
                'code_error' => 4,
                'success' => false,
                'error' => true,
                'message' => 'Aucun utilisateur trouvé'
            );
            return new JsonResponse($result);
        }

        if (!$this->setDataInUtilisateur($utilisateur, $request)) {
            $result = array(
                'code_error' => 4,
                'success' => false,
                'error' => true,
                'message' => 'Une erreur est survenue lors de insertion'
            );
            return new JsonResponse($result);
        }
        $result = array(
            'code_error' => 0,
            'success' => true,
            'error' => false,
            'message' => 'success'
        );
        return new JsonResponse($result);

    }

    /**
     * @param Utilisateur $utilisateur
     * @param $request
     * @return bool
     */
    private function setDataInUtilisateur(Utilisateur $utilisateur, $request)
    {


        try {
            $utilisateur->setRoles(array('ROLE_USER'));

            $utilisateur->setSalt('bcrypt');
            $utilisateur->setCreatedAt(new \DateTime('now'));
            $utilisateur->setDateCreation(new \DateTime('now'));
            $utilisateur->setIsEnable(true);
            $utilisateur->setPassword(md5(uniqid($request->get('email'))));
            $utilisateur->setUserToken(md5(uniqid($request->get('username') . '' . $request->get('password'), true)));

            if ($request->get('photo')) {
                $utilisateur->setCheminPhoto($request->get('photo'));
            }

            if ($request->get('email')) {
                $utilisateur->setEmail($request->get('email'));
            }

            if ($request->get('username')) {
                $utilisateur->setUsername($request->get('username'));
            }

            if ($request->get('nom')) {
                $utilisateur->setNom($request->get('nom'));
            }

            if ($request->get('prenom')) {
                $utilisateur->setPrenom($request->get('prenom'));
            }

            if ($request->get('sexe')) {
                $utilisateur->setSexe($request->get('sexe'));
            }

            if ($request->get('password')) {
                $password = $this->encodePassword($request->get('password'));
                $utilisateur->setPassword($password);
                
            }
            else {

                $parameter = $this->getParameterMail();
                $pass = $this->generatePassword();
                $email = $request->get('email');
                $prenom = $request->get('prenom');
                $body = $parameter->getTemplateInscription();

                $mailerService = $this->getMailerService();
                $res = str_replace('{{prenom}}',$prenom,$body);
                $res = str_replace('{{email}}',$email ,$res);
                $res = str_replace('{{password}}',$pass,$res);
                $res = str_replace('{{adresseSociete}}', $parameter->getAdresseSociete(), $res);
                
                $mailerService->setSubject($parameter->getSubjectInscription());
                $mailerService->setFrom($parameter->getEmailSite());
                $mailerService->setTo($email);
                $mailerService->addParams('body',$res);

                if($mailerService->send()){
                    $utilisateur->setPassword($this->encodePassword($pass));
                }
            }

            if ($request->get('days') && $request->get('month') && $request->get('years')) {
                $days = $request->get('days');
                $month = $request->get('month');
                $years = $request->get('years');

                $dateNaissance = $years . '-' . $month . '-' . $days;
                $utilisateur->setDateNaissance(new \DateTime($dateNaissance));
            }


            if ($request->get('telephone')) {
                $utilisateur->setTelephone($request->get('telephone'));
            }

            if ($request->get('adresse1')) {
                $utilisateur->setAdresse1($request->get('adresse1'));
            }

            if ($request->get('adresse2')) {
                $utilisateur->setAdresse2($request->get('adresse2'));
            }

            if ($request->get('adresse3')) {
                $utilisateur->setAdresse3($request->get('adresse3'));
            }

            if ($request->get('fax')) {
                $utilisateur->setFax($request->get('fax'));
            }

            if ($request->get('ville')) {
                $utilisateur->setVille($request->get('ville'));
            }
            if ($request->get('pays')) {
                $utilisateur->setPays($request->get('pays'));
            }

            $this->get('doctrine.orm.entity_manager')->persist($utilisateur);
            $this->get('doctrine.orm.entity_manager')->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    private function setDataFromAndoidInUtilisateur(Utilisateur $utilisateur, $request)
    {
        try {
            $utilisateur->setCreatedAt(new \DateTime('now'));
            if ($request->get('email')) {
                $utilisateur->setEmail($request->get('email'));
            }
            if ($request->get('prenom')) {
                $utilisateur->setPrenom($request->get('prenom'));
            }
            $utilisateur->setUsername($request->get('email'));
            $utilisateur->setSalt('bcrypt');
            $utilisateur->setRoles(array('ROLE_USER'));
            $utilisateur->setCreatedAt(new \DateTime('now'));
            $utilisateur->setUserToken(md5(uniqid($request->get('email') . '' . $request->get('prenom'), true)));
            $utilisateur->setDateCreation(new \DateTime('now'));

            $this->get('doctrine.orm.entity_manager')->persist($utilisateur);
            $this->get('doctrine.orm.entity_manager')->flush();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}

