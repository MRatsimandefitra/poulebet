<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends ApiController
{
    const FORM_EMAIL = 'Api\DBBundle\Request\Email';
    const FORM_LIST_EMAIL = 'Api\DBBundle\Request\ListEmail';

    public function indexAction(Request $request)
    {
        $utilisateur = $this->get('doctrine.orm.entity_manager')->getRepository('ApiDBBundle:Utilisateur')->findOneBy(array('email' => $request->request->get('email')));
        if (!$utilisateur) {
            $utilisateur = new Utilisateur();
        }
        $form = $this->formPost(self::FORM_EMAIL, $utilisateur);
        $form->handleRequest($request);
        if ($form->isValid()) {
            $utilisateur->setUsername($form['email']->getData());
            $utilisateur->setIsEnable(true);
            $utilisateur->setSalt('bcrypt');
            $utilisateur->setRoles(array('ROLE_USER'));
            $utilisateur->setNom('anonymous');
            $utilisateur->setPrenom('anonymous');

            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($utilisateur);
            $password = $encoder->encodePassword($request->request->get('password'), $utilisateur->getSalt());
            $utilisateur->setPassword($password);


            $utilisateur->setCreatedAt(new \DateTime('now'));
            //$utilisateur->setUserToken(md5(md5(uniqid($utilisateur->getUsername() . '' . $utilisateur->getPassword(), true));

            $this->get('doctrine.orm.entity_manager')->persist($utilisateur);
            $this->get('doctrine.orm.entity_manager')->flush();

        }
        return $this->render('BackAdminBundle:Email:index.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
