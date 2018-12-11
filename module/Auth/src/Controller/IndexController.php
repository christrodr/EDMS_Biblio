<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Controller;

use Auth\Model\UtilisateurTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Auth\Form\LoginForm;
use Auth\Model\Utilisateur;
use Zend\Authentication\AuthenticationService;

class IndexController extends AbstractActionController {

    private $table;

    // Add this constructor:
    public function __construct(UtilisateurTable $table) {
	$this->table = $table;
    }

    public function indexAction() {
	$form = new LoginForm();
	$form->get('submit')->setValue('Login');

	$request = $this->getRequest();

	if (!$request->isPost()) {
	    return ['form' => $form];
	}

	$utilisateur = new Utilisateur();
	$form->setInputFilter($utilisateur->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return ['form' => $form];
	}

	$utilisateur->exchangeArray($form->getData());
	$this->table->logUser($utilisateur);
	return $this->redirect()->toRoute('home');
    }

    public function logoutAction() {
	$auth = new AuthenticationService();
	if ($auth->hasIdentity()) {
	    $identity = $auth->getIdentity();
	}
	$auth->clearIdentity();
	$sessionManager = new \Zend\Session\SessionManager();
	$sessionManager->forgetMe();

	return $this->redirect()->toRoute('auth', array('controller' => 'index', 'action' => 'index'));
    }

}
