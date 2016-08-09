<?php

namespace Api\CommonBundle\Utils;
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
    private $container;
    private $parameters;

    const TEMPLATE = 'ApiCommonBundle:Email:mail.html.twig';
    const ENTITY_NAME = 'ApiDBBundle:ParameterMail';


    function __construct(Container $container)
    {
        $this->container = $container;
        /*$container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());
        $container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());
        $container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());
        $container->setParameter('mailer_transport', $container->get('doctrine.orm.entity_manager')->getRepository(self::ENTITY_NAME)->findAll()->getServeurSMTP());*/


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
        return $this->template = self::TEMPLATE;
    }

    /**
     * @param mixed $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     */



    public function send()
    {

        $message = \Swift_Message::newInstance()
            ->setSubject($this->getSubject())
            ->setFrom($this->getFrom())
            ->setTo($this->getTo())
            ->setBody(
                $this->renderView(
                    $this->getTemplate()
                /*array('name' => $name)*/
                ),
                'text/html'
            );
        $this->container->get('mailer')->send($message);
    }
}