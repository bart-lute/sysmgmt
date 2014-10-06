<?php

namespace LF14\SysMgmtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LF14SysMgmtBundle:Default:index.html.twig');
    }
}
