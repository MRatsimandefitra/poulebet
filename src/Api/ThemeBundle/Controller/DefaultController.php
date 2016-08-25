<?php

namespace Api\ThemeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ApiThemeBundle:Default:index.html.twig');
    }
}
