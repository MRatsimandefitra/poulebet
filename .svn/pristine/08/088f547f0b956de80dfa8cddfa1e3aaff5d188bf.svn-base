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

        $username = $request->get('username');
        $email = $request->get('email');
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
        $username = $request->get('username');
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


}

