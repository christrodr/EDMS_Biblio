<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use DomainException;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Filter\ToInt;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Validator\StringLength;

class Categorie implements InputFilterAwareInterface {

    public $id;
    public $nom;
    public $archiver;
    private $inputFilter;

    public function exchangeArray(array $data) {
	$this->id = !empty($data['id_Categorie']) ? $data['id_Categorie'] : null;
	$this->nom = !empty($data['nom']) ? $data['nom'] : null;
	$this->archiver = !empty($data['archiver']) ? $data['archiver'] : null;
    }

    public function getArrayCopy() {
	return [
	    'id_Categorie' => $this->id,
	    'nom' => $this->nom,
	    'archiver' => $this->archiver,
	];
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
	    'name' => 'id',
	    'required' => true,
	    'filters' => [
		['name' => ToInt::class],
	    ],
	]);

	$inputFilter->add([
	    'name' => 'nom',
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
