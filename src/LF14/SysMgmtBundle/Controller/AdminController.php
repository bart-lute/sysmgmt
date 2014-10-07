<?php

namespace LF14\SysMgmtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LF14\SysMgmtBundle\Model\Contact;
use LF14\SysMgmtBundle\Form\Type\ContactType;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('LF14SysMgmtBundle:Admin:index.html.twig');
    }
    
}
