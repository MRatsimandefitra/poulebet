<?php

namespace Back\SignupBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\User;
use Api\DBBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends ApiController
{
    const FORM_USER = 'Api\DBBundle\Form\UserType';

    public function registerAction(Request $request)
    {
      /*  if ($this->getUser()) {
            return $this->redirectToRoute('index_home');
        }
        $user = new User();

        $form = $this->formPost(self::FORM_USER, $user);
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);

        //$user->setSalt($encoder);

        $form->handleRequest($request);

        if ($form->isValid()) {

            $password = $encoder->encodePassword($form->get('password')->getData(), $user->getSalt());
            //var_dump($form->get('password')->getData()); die;
            $user->setPassword($password);
            $stateInsert = $this->insert($user, $this->getMsg());
            if ($stateInsert) {
                return $this->redirectToRoute('index_home');
            } else {
                return $this->redirectToRoute('login');
            }

        }*/
        return $this->render('FrontSignupBundle:Register:register.html.twig', array(
           /* 'form' => $form->createView()*/
        ));
    }

}
