<?php

namespace Api\CommonBundle\Utils;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\Container;

/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 09/08/2016
 * Time: 15:38
 */

class Mailer
{

    private $subject;
    private $from;
    private $to;
    private $template;
    private $body;
    private $container;

    private $params;

    private $parameters;

    const TEMPLATE = 'ApiCommonBundle:Email:mail.html.twig';
    const ENTITY_NAME = 'ApiDBBundle:ParameterMail';


    function __construct(Container $container)
    {
        $this->container = $container;

        $this->params = array();

        /*$container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());
        $container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());
        $container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());
        $container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());*/


    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param mixed $from
     */
    public function setFrom($from)
    {
        $this->from = $from;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param mixed $to
     */
    public function setTo($to)
    {
        $this->to = $to;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }


    public function addParams($key,$value){
        $this->params[$key] = $value; 
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }



    public function setParameters(){
        $entities = $this->container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll();
        foreach($entities as $vEmail){
            $email  = $vEmail->getEmailSite();
            $port = $vEmail->getPortSMTP();
            $user = $vEmail->getUserSMTP();
            $password = $vEmail->getPasswordSMTP();
            $serveur = $vEmail->getServeurSMTP();
            $security = $vEmail->getSeuriteSMTP();
        }
        $transport = \Swift_SmtpTransport::newInstance($serveur,$port)
            ->setUsername($user)
            ->setPassword($password)
            ->setEncryption($security)

        ;
        $mailer = \Swift_Mailer::newInstance($transport);
        return $mailer;
    }

    public function send()
    {
        try{

            $mailer = $this->setParameters();
        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom($this->getFrom())
            ->setTo($this->getTo())
            ->setBody(

                    $this->params['body']


                
                /*array('name' => $name)*/
                ,
                'text/html'
            );
            $mailer->send($message);
            return true;
        }
        catch(Exception $ex){
            return false;
        }
        return false;
    }
}