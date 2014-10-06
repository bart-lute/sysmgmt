<?php

namespace LF14\SysMgmtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('LF14SysMgmtBundle:Default:admin_index.html.twig');
    }
}
