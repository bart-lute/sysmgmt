<?php

namespace LF14\SysMgmtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LF14\SysMgmtBundle\Model\Contact;
use LF14\SysMgmtBundle\Form\Type\ContactType;

class ContactController extends Controller
{

	public function listAction() {
		return $this->render('LF14SysMgmtBundle:Admin:contact.list.html.twig');
	}
	
	public function newAction()
    {
    	$contact = new Contact();
    	$form = $this->createForm(new ContactType(), $contact);
    	
    	return $this->render('LF14SysMgmtBundle:Admin:contact.new.html.twig', array(
    		'form' => $form->createView(),
    	));
    }
}
