<?php

namespace Ws\RestBundle\Controller;

use Api\DBBundle\Entity\Utilisateur;
use Api\DBBundle\Entity\Device;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class InscriptionController extends ApiRestController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';

    const ENTITY_PARAMETER_MAIL = 'ApiDBBundle:ParameterMail';

    const ENTITY_DROIT_ADMIN = '';
    const ENTITY_DROIT = 'ApiDBBundle:';

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


    public function getProfilUtilisateurAction(Request $request)
    {

        $username = $request->query->get('username');
        $data = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->findBy(array('username' => $username));
        if (!$data) {
            return new JsonResponse(array(
                'code' => 'error.exist',
                'code_erreur' => 1,
                'isexist' => false,
                'message' => 'Utilisateur existant'
            ));
        }
        foreach ($data as $vData) {
            $response = array(
                'photo' => $vData->getCheminPhoto(),
                'nom' => $vData->getNom(),
                'prenom' => $vData->getPrenom(),
                'sexe' => $vData->getSexe(),
                'days' => $vData->getDateNaissance()->format('d'),
                'month' => $vData->getDateNaissance()->format('m'),
                'years' => $vData->getDateNaissance()->format('Y'),
                'telephone' => $vData->getTelephone(),
                'fax' => $vData->getFax(),
                'username' => $vData->getUsername(),
                'email' => $vData->getEmail(),
                'adresse1' => $vData->getAdresse1(),
                'adresse2' => $vData->getAdresse2(),
                'adresse3' => $vData->getAdresse3(),
                'ville' => $vData->getVille(),
                'pays' => $vData->getPays(),
            );
        }

        return new JsonResponse($response);
    }

    public function postProfilUtilisateurAction(Request $request)
    {
        $username = $request->get('username');
        $utilisateur = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->findOneBy(array('username' => $username));
        if (!$utilisateur) {
            return new JsonResponse(array(
                'code' => 'not.exist',
                'isexist' => false,
                'code_erreur' => 4,
                'error' => 'not.found.utilisateur',
                'success' => false,
                'error' => false,
                'message' => 'Utilisateur non trouvé'
            ));
        }

        if ($this->setDataInUtilisateur($utilisateur, $request)) {
            return new JsonResponse(array(
                'code' => 'OK',
                'code_erreur' => 0,
                'success' => true,
                'error' => false,
                'message' => 'Utilisateur a bien été inserer'
            ));
        }

        return new JsonResponse(array(
            'code' => 'NOK',
            'success' => false,
            'code_erreur' => 2,
            'error' => true,
            'message' => 'Une erreur est survenue lors d ajout utilisateur'
        ));
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

