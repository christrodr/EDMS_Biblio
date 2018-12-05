<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Form;

use Zend\Form\Form;

class CategorieForm extends Form {

    public function __construct($name = null) {
	// We will ignore the name provided to the constructor
	parent::__construct('categorie');

	$this->add([
	    'name' => 'id',
	    'type' => 'hidden',
	]);
	$this->add([
	    'name' => 'nom',
	    'type' => 'text',
	    'options' => [
		'label' => 'Nom de la catégorie',
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
