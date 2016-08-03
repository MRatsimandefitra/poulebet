<?php

namespace Api\CommonBundle\Twig;

use Symfony\Component\DependencyInjection\Container;

/**
 * Created by PhpStorm.
 * User: fy.andrianome
 * Date: 03/08/2016
 * Time: 15:14
 */
class OutputExtension extends \Twig_Extension
{

    private $container;
    private $request;
    private $templating;
    private $translator;
    private $em;

    public function __construct(Container $container)
    {
        $this->container = $container;
        //  $this->em = $container->get('doctrine')->getEntityManager();
    }

    public function initRuntime(\Twig_Environment $env)
    {
        $this->env = $env;
    }

    public function getFilters()
    {
        return array();
    }

    public function getFunctions()
    {
        return array(
            'verify' => new \Twig_Function_Method($this, 'verify'),
        );
    }

    public function verify($id)
    {
        $adminDroit = $this->container->get('doctrine.orm.entity_manager')->getRepository('ApiDBBundle:DroitAdmin')->findOneBy(array('id' => $id, 'admin' => $this->getCurrentUser()));
        if ($adminDroit->getAjout()) {
            return 'ajout';
        }
        if ($adminDroit->getLecture()) {
            return 'lecture';
        }
        if ($adminDroit->getModification()) {
            return 'modification';
        }
        if ($adminDroit->getSuppression()) {
            return 'suppression';
        }
    }

    private function getCurrentUser()
    {
        return $this->container->get('security.token_storage')->getToken()->getUser();
    }

    public function getName()
    {
        return 'OutputExtension';
    }
}