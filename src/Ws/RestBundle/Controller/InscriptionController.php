<?php

namespace Ws\RestBundle\Controller;

use Api\DBBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class InscriptionController extends ApiRestController
{
    const ENTITY_UTILISATEUR = 'ApiDBBundle:Utilisateur';

    public function postUtilisateurAction(Request $request)
    {

        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $data = $this->get('doctrine.orm.default_entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->testIfUserExist($username, $email);
        if ($data) {
            return new JsonResponse(array(
                'code' => 'error.exist',
                'isexist' => true
            ));
        }
        $utilisateur = new Utilisateur();

        if ($this->setDataInUtilisateur($utilisateur, $request)) {
            return new JsonResponse(
                array(
                    'code' => 'OK',
                    'isOk' => true
                )
            );
        }
        return new JsonResponse(
            array(
                'code' => 'NOK',
                'isOk' => false
            )
        );
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getProfilUtilisateurAction(Request $request)
    {

        $username = $request->query->get('username');
        $data = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->findBy(array('username' => $username));
        if (!$data) {
            return new JsonResponse(array(
                'code' => 'error.exist',
                'isexist' => false
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
        $username = $request->request->get('username');
        $utilisateur = $this->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_UTILISATEUR)->findOneBy(array('username' => $username));
        if (!$utilisateur) {
            return new JsonResponse(array(
                'code' => 'not.exist',
                'isexist' => false,
                'error' => 'not.found.utilisateur',
                'success' => false,
                'error' => false
            ));
        }

        if ($this->setDataInUtilisateur($utilisateur, $request)) {
            return new JsonResponse(array(
                'code' => 'OK',
                'success' => true,
                'error' => false
            ));
        }

        return new JsonResponse(array(
            'code' => 'NOK',
            'success' => false,
            'error' => true
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

            $utilisateur->setUserToken(md5(uniqid($request->request->get('username') . '' . $request->request->get('password'), true)));

            if ($request->request->get('photo')) {
                $utilisateur->setCheminPhoto($request->request->get('photo'));
            }

            if ($request->request->get('email')) {
                $utilisateur->setEmail($request->request->get('email'));
            }

            if ($request->request->get('username')) {
                $utilisateur->setUsername($request->request->get('username'));
            }

            if ($request->request->get('nom')) {
                $utilisateur->setNom($request->request->get('nom'));
            }

            if ($request->request->get('prenom')) {
                $utilisateur->setPrenom($request->request->get('prenom'));
            }

            if ($request->request->get('sexe')) {
                $utilisateur->setSexe($request->request->get('sexe'));
            }

            if ($request->request->get('password')) {
                $factory = $this->get('security.encoder_factory');
                $encoder = $factory->getEncoder($utilisateur);
                $password = $encoder->encodePassword($request->request->get('password'), $utilisateur->getSalt());
                $utilisateur->setPassword($password);
            }


            if ($request->request->get('days') && $request->request->get('month') && $request->request->get('years')) {
                $days = $request->request->get('days');
                $month = $request->request->get('month');
                $years = $request->request->get('years');

                $dateNaissance = $years . '-' . $month . '-' . $days;
                $utilisateur->setDateNaissance(new \DateTime($dateNaissance));
            }


            if ($request->request->get('telephone')) {
                $utilisateur->setTelephone($request->request->get('telephone'));
            }

            if ($request->request->get('adresse1')) {
                $utilisateur->setAdresse1($request->request->get('adresse1'));
            }

            if ($request->request->get('adresse2')) {
                $utilisateur->setAdresse2($request->request->get('adresse2'));
            }

            if ($request->request->get('adresse3')) {
                $utilisateur->setAdresse3($request->request->get('adresse3'));
            }

            if ($request->request->get('fax')) {
                $utilisateur->setFax($request->request->get('fax'));
            }

            if ($request->request->get('ville')) {
                $utilisateur->setVille($request->request->get('ville'));
            }
            if ($request->request->get('pays')) {
                $utilisateur->setPays($request->request->get('pays'));
            }

            $this->get('doctrine.orm.entity_manager')->persist($utilisateur);
            $this->get('doctrine.orm.entity_manager')->flush();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }


}

