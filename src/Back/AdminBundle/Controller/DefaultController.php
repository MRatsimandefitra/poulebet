<?php

namespace Back\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('BackAdminBundle:Default:index.html.twig');
    }
}
