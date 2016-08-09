<?php

namespace Back\AdminBundle\Controller;

use Api\CommonBundle\Controller\ApiController;
use Api\DBBundle\Entity\Societe;
use Api\DBBundle\Entity\ParameterMail;
use Api\DBBundle\Entity\TemplateMail;
use Api\DBBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends ApiController
{
    const FORM_EMAIL = 'Api\DBBundle\Form\EmailBoType';
    const FORM_LIST_EMAIL = 'Api\DBBundle\Request\ListEmail';
    const FORM_PARAMETRE_EMAIL = 'Api\DBBundle\Form\ParameterMailType';
    const FORM_ADRESSE = 'Api\DBBundle\Form\SocieteType';
    const ENTITY_ADRESSE = 'ApiDBBundle:Societe';
    const ENTITY_PARAMETRE_EMAIL = 'ApiDBBundle:ParameterMail';
    const ENTITY_TEMPLATE_MAIl = 'ApiDBBundle:TemplateMail';
    const FORM_TEMPLATE_EMAIL = 'Api\DBBundle\Form\TemplateMailType';

    public function indexAction(Request $request)
    {
        $utilisateur = $this->get('doctrine.orm.entity_manager')->getRepository('ApiDBBundle:Utilisateur')->findOneBy(array('email' => $request->request->get('email')));
        if (!$utilisateur) {
            $utilisateur = new Utilisateur();
        }
        $emai = $request->request->get('email');
        if ($emai != null && $emai != "") {
            $utilisateur->setEmail($request->request->get('email'));
            if($this->setInUtilisateur($utilisateur, $request)){
                $this->get('doctrine.orm.entity_manager')->persist($utilisateur);
                $this->get('doctrine.orm.entity_manager')->flush();
            }

        }

        $form = $this->formPost(self::FORM_EMAIL, $utilisateur);

        $form->handleRequest($request);

        if ($form->isValid()) {

           // var_dump($this->setInUtilisateur($utilisateur, $request)); die;
            if(is_object($utilisateur->getEmail())){
                $utilisateur = $utilisateur->getEmail();
            }
            if($this->setInUtilisateur($utilisateur, $request)){
                $this->get('doctrine.orm.entity_manager')->persist($utilisateur);
                $this->get('doctrine.orm.entity_manager')->flush();
                $this->addFlash('success' , 'success');
                return $this->redirectToRoute('index_admin_email');
            }else{
                $this->addFlash('error', 'error');
                return $this->redirectToRoute('index_admin_email');
            }
        }
        return $this->render('BackAdminBundle:Email:index.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function setInUtilisateur($utilisateur, $request){
        try{
            $utilisateur->setUsername($utilisateur->getEmail());

            $utilisateur->setIsEnable(true);
            $utilisateur->setSalt('bcrypt');
            $utilisateur->setRoles(array('ROLE_USER'));
            $utilisateur->setNom('anonymous');
            $utilisateur->setPrenom('anonymous');
            $utilisateur->setDateNaissance(new \DateTime('now'));
            $utilisateur->setDateCreation(new \DateTime('now'));
            $factory = $this->get('security.encoder_factory');
            $encoder = $factory->getEncoder($utilisateur);
            $password = $encoder->encodePassword($request->request->get('password'), $utilisateur->getSalt());
            $utilisateur->setPassword($password);

            $utilisateur->setCreatedAt(new \DateTime('now'));
            $utilisateur->setUserToken(md5(uniqid('keyword', true)));

            return true;
        }catch(\Exception $e){
            return false;
        }


    }

    public function parametersAction(Request $request)
    {

        // 1- get adresse
        $adresses = $this->getAllEntity(self::ENTITY_ADRESSE);
        if (!$adresses) {
            $adresses = new Societe();
        }
        $formAdress = $this->formPost(self::FORM_ADRESSE, $adresses);
        $formAdress->handleRequest($request);
        if ($formAdress->isValid()) {
            $this->insert($adresses, array('success' => 'success', 'error' => 'error'));

        }
        // 2 - get if exist params mail temporary
        if (!$this->getAllEntity(self::ENTITY_PARAMETRE_EMAIL)) {
            $parameter = new ParameterMail();
        }
        $formParameters = $this->formPost(self::FORM_PARAMETRE_EMAIL, $parameter);

        $formParameters->handleRequest($request);
        if ($formParameters->isValid()) {
            $this->insert($parameter, array('success' => 'success', 'error' => 'error'));
        }

        // 3 - mail tmp
        $templateMailTmp = $this->getRepoFrom(self::ENTITY_TEMPLATE_MAIl, array('libelleMail' => 'tmp'));
        if (!$templateMailTmp) {
            $templateMailTmp = new TemplateMail();
            $templateMailTmp->setLibelleMail('tmp');
        }
        $formTMTmp = $this->formPost(self::FORM_TEMPLATE_EMAIL, $templateMailTmp);
        $formTMTmp->handleRequest($request);
        if ($formTMTmp->isValid()) {
            $this->insert($templateMailTmp, array('success' => 'success', 'error' => 'error'));
        }
        // 4  - Mail Change
        $templateMailChange = $this->getRepoFrom(self::ENTITY_TEMPLATE_MAIl, array('libelleMail' => 'change'));

        if (!$templateMailChange) {
            $templateMailChange = new TemplateMail();
            $templateMailChange->setLibelleMail('change');
        }
        $formChange = $this->formPost(self::FORM_TEMPLATE_EMAIL, $templateMailChange);
        $formChange->handleRequest($request);

        if ($formChange->isValid()) {
            $this->insert($templateMailChange, array('success' => 'success', 'error' => 'error'));
        }

        return $this->render('BackAdminBundle:Email:parameters.html.twig', array(
            'formParameter' => $formParameters->createView(),
            'formAdresse' => $formAdress->createView(),
            'formChange' => $formChange->createView(),
            'formTmp' => $formTMTmp->createView()
        ));
    }

}
