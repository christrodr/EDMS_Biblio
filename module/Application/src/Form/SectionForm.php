<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

class SectionForm extends Form {

    public function __construct($name = null) {
	// We will ignore the name provided to the constructor
	parent::__construct('section');

	$this->add([
	    'name' => 'id',
	    'type' => 'hidden',
	]);
	$this->add([
	    'name' => 'nom',
	    'type' => 'text',
	    'options' => [
		'label' => 'Title',
	    ],
	]);
	$this->add([
	    'name' => 'submit',
	    'type' => 'submit',
	    'attributes' => [
		'value' => 'Valider',
		'id' => 'submitbutton',
	    ],
	]);
    }

}
