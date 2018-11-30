<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\SectionTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\SectionForm;
use Application\Model\Section;

class SectionController extends AbstractActionController {

    private $table;

    public function __construct(SectionTable $table) {
	$this->table = $table;
    }

    public function indexAction() {
	return new ViewModel();
    }

    public function listAction() {
	return new ViewModel([
	    'sections'=> $this->table->fetchAll(),
	]);
    }

    public function addAction() {
	$form=new SectionForm();
	$form->get('submit')->setValue('Créer');
	
	$request= $this->getRequest();
	
	if(!$request->isPost()){
	    return['form'=>$form];
	}
	
	$section=new Section();
	$form->setInputFilter($section->getInputFilter());
	$form->setData($request->getPost());
	
	if(!$form->isValid()){
	    return['form'=>$form];
	}
	
	$section->exchangeArray($form->getData());
	$this->table->saveSection($section);
	return $this->redirect()->toRoute('section/list');
    }

    public function editAction() {
	return new ViewModel();
    }

    public function archiveAction() {
	return new ViewModel();
    }

}
