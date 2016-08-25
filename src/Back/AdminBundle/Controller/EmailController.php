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
    //const ENTITY_ADRESSE = 'ApiDBBundle:Societe';
    const ENTITY_PARAMETRE_EMAIL = 'ApiDBBundle:ParameterMail';
    const ENTITY_TEMPLATE_MAIl = 'ApiDBBundle:TemplateMail';
    const FORM_TEMPLATE_EMAIL = 'Api\DBBundle\Form\TemplateMailType';
    const FORM_ADMIN_DROIT = 'Api\DBBundle\Form\DroitAdminType';
    const ENTITY_DROIT_ADMIN = 'ApiDBBundle:DroitAdmin';
    const ENTITY_DROIT = 'ApiDBBundle:Droit';

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
            'form' => $form->createView(),
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
        $droit = $this->getRepo(self::ENTITY_DROIT)->findOneByFonctionnalite('Gestion Email');
        $parameters = $this->getAllEntity(self::ENTITY_PARAMETRE_EMAIL);
        $currentDroitAdmin = $this->getRepo(self::ENTITY_DROIT_ADMIN)->findOneBy(array('admin' => $this->getUser(), 'droit' => $droit ));

        if (!$parameters ) {
            $parameter = new ParameterMail();
        }
        else {
          $parameter = $parameters[count($parameters)-1];  
        }
        $formParameters = $this->formPost(self::FORM_PARAMETRE_EMAIL, $parameter);

        $formParameters->handleRequest($request);
        if ($formParameters->isValid()) {
            $this->insert($parameter, array('success' => 'success', 'error' => 'error'));
        }

        return $this->render('BackAdminBundle:Email:parameters.html.twig', array(
            'formParameter' => $formParameters->createView(),
            'currentAdmin' => $currentDroitAdmin
        ));
    }

    public function testEmailAction(){
       /* $hostDb = "smtp.gmail.com";
        $portDb = 465;
        $portTLS = 587;
        //$config = $this->get('swiftmailer.mailer.default.transport.real')->getPort();
        $userDb = 'dev.ywoume@gmail.com';
        $passwordDb = 'tsilaina150';
        $transport = \Swift_SmtpTransport::newInstance($hostDb,$portTLS)
            ->setUsername($userDb)
            ->setPassword($passwordDb)
            ->setEncryption('tls')

        ;
        $mailer = \Swift_Mailer::newInstance($transport);
        $message = \Swift_Message::newInstance()
            ->setSubject('sujet')
            ->setFrom('dev.ywoume@gmail.com')
            ->setTo('ywoume@gmail.com')
            ->setBody('okok baina')
        ;
        $mailer->send($message);*/
        $parameters = $this->get('doctrine.orm.entity_manager')->getRepository('ApiDBBundle:ParameterMail')->findAll();

        foreach($parameters as $vParameter){
            $body = $vParameter->getTemplateInscription();
            $subject = $vParameter->getSubjectInscription();
        }

     //   die('okok');
        $sm = $this->get('mail.manager');
        $sm->setSubject($subject);
        $sm->setFrom('dev.ywoume@gmail.com');
        $sm->setTo('ywoume@gmail.com');
        $sm->setBody($body);
        $sm->setParams(array('email' => 'coco@gmail.com', 'password' => 'mon passs', 'adresseSociete' => 'mon adresse'));

        $sm->send();
        die('okok');
    }
}
