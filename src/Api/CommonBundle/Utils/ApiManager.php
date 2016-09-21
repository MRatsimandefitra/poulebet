<?php
/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 13/09/2016
 * Time: 13:48
 */

namespace Api\CommonBundle\Utils;


use Symfony\Component\DependencyInjection\Container;

class ApiManager implements \InterfaceApi {

    private $container;

    private $em;

    private $template;

    private $logger;

    private $kernel;

    public function __construct(Container $container){
        $this->container = $container;
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->kernel = $container->get('kernel');


    }

    /**
     * @return mixed
     */
    protected function getContainer()
    {
        return $this->container;
    }

    /**
     * @param mixed $container
     */
    protected function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    protected function getEm()
    {
        return $this->em;
    }

    /**
     * @param mixed $em
     */
    protected function setEm($em)
    {
        $this->em = $em;
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
    protected function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * @return mixed
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param mixed $logger
     */
    protected function setLogger($logger)
    {
        $this->logger = $logger;
    }

}