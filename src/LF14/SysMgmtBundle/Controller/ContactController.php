<?php

namespace LF14\SysMgmtBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LF14\SysMgmtBundle\Model\Contact;
use LF14\SysMgmtBundle\Form\Type\ContactType;
use LF14\SysMgmtBundle\Model\ContactPeer;
use LF14\SysMgmtBundle\Model\ContactQuery;

class ContactController extends Controller
{

	public function listAction() {
		
		$contacts = ContactQuery::create()->orderByName()->find();

		$tmpl_vars = Array();

		if (count($contacts) > 0) {
			$tmpl_vars['contacts'] = $contacts;
		} 
		
		return $this->render('LF14SysMgmtBundle:Admin:contact.list.html.twig', $tmpl_vars);
	}
	
    public function formAction($id) {
    	
    	if (!$id) {
    		$contact = new Contact();
    		$form = $this->createForm(new ContactType(), $contact);
    	} else {
    		$contact = ContactQuery::create()->findPk($id);
    	}
    	
    	$form = $this->createForm(new ContactType(), $contact);
    	
    	$request = $this->getRequest();
    	
    	if ($request->getMethod() === 'POST') {
    		
    		$form->handleRequest($request);
    		
    		if ($form->isValid()) {
    			if (!$form->get('cancel')->isClicked()) {
	    			$contact->save();
    			}
    			return $this->redirect($this->generateUrl('lf14sm_admin_contacts'));
    		}
    	}
    	
    	return $this->render('LF14SysMgmtBundle:Admin:contact.form.html.twig', array(
    		'form' => $form->createView(),
    	));
    	    	
    }
}
