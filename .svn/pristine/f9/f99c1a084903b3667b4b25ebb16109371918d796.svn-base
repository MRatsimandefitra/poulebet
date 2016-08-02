<?php

namespace Back\SignupBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Front\SignupBundle\Request\Login;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends ApiController
{
    const FORM_LOGIN = 'Api\DBBundle\Form\LoginType';

    public function loginAction(Request $request)
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('index_home');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // $login = new Login();
        //$form = $this->formPost(self::FORM_LOGIN, $login);
        // $form->handleRequest($request);
        /*if ($form->isValid()) {

        }*/
        return $this->render('BackSignupBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));

    }
    public function loginCheckAction()
    {

        // this controller will not be executed,
        // as the route is handled by the Security system
    }

    public function logoutAction()
    {
        // this controller will not be executed,
        // as the route is handled by the Security system
    }

}
