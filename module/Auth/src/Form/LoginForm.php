<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Form;

use Zend\Form\Form;

class LoginForm extends Form {

    public function __construct($name = null) {
	// We will ignore the name provided to the constructor
	parent::__construct('utilisateur');

	$this->add([
	    'name' => 'user',
	    'type' => 'text',
	    'options' => [
		'label' => 'Nom d\'utilisateur',
	    ],
	]);
	$this->add([
	    'name' => 'pass',
	    'type' => 'password',
	    'options' => [
		'label' => 'Mot de passe',
	    ],
	]);
	$this->add([
	    'name' => 'rememberme',
	    'type' => 'checkbox',
	    'options' => [
		'label' => 'Se souvenir de moi',
	    ],
	]);
	$this->add([
	    'name' => 'submit',
	    'type' => 'submit',
	    'attributes' => [
		'value' => 'Login',
		'id' => 'submitbutton',
	    ],
	]);
    }

}
