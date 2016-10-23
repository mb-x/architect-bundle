<?php

namespace Mbx\SymfonyBootstrapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MbxSymfonyBootstrapBundle:Default:index.html.twig');
    }
}
