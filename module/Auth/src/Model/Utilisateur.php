<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Utilisateur {

    public $id;
    public $user;
    public $pass;
    public $isAdmin;
    public $rememberme;
    private $inputFilter;

    public function exchangeArray(array $data) {
	$this->id = !empty($data['id_Utilisateur']) ? $data['id_Utilisateur'] : null;
	$this->user = !empty($data['user']) ? $data['user'] : null;
	$this->pass = !empty($data['pass']) ? $data['pass'] : null;
	$this->rememberme = !empty($data['rememberme']) ? $data['rememberme'] : null;
	$this->isAdmin = !empty($data['admin']) ? $data['admin'] : null;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
	throw new DomainException(sprintf(
		'%s does not allow injection of an alternate input filter', __CLASS__
	));
    }

    public function getInputFilter() {
	if ($this->inputFilter) {
	    return $this->inputFilter;
	}

	$inputFilter = new InputFilter();

	$inputFilter->add([
	    'name' => 'user',
	    'required' => true,
	    'filters' => [
		['name' => StripTags::class],
		['name' => StringTrim::class],
	    ],
	    'validators' => [
		[
		    'name' => StringLength::class,
		    'options' => [
			'encoding' => 'UTF-8',
			'min' => 1,
			'max' => 100,
		    ],
		],
	    ],
	]);

	$inputFilter->add([
	    'name' => 'pass',
	    'required' => true,
	    'filters' => [
		['name' => StripTags::class],
		['name' => StringTrim::class],
	    ],
	    'validators' => [
		[
		    'name' => StringLength::class,
		    'options' => [
			'encoding' => 'UTF-8',
			'min' => 1,
			'max' => 100,
		    ],
		],
	    ],
	]);

	$this->inputFilter = $inputFilter;
	return $this->inputFilter;
    }

}
