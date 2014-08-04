<?php

namespace AG\PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AGPrincipalBundle:Default:index.html.twig');
    }

    public function catalogoAdminAction()
    {
        return $this->render('AGPrincipalBundle:Default:catalogoAdmin.html.twig');
    }
}