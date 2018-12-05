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
	    'sections' => $this->table->fetchAll(),
	]);
    }

    public function addAction() {
	$form = new SectionForm();
	$form->get('submit')->setValue('Créer');

	$request = $this->getRequest();

	if (!$request->isPost()) {
	    return['form' => $form];
	}

	$section = new Section();
	$form->setInputFilter($section->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return['form' => $form];
	}

	$section->exchangeArray($form->getData());
	$this->table->saveSection($section);
	return $this->redirect()->toRoute('section', ['action' => 'list']);
    }

    public function editAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	if (0 === $id) {
	    return $this->redirect()->toRoute('section', ['action' => 'add']);
	}

	// Retrieve the section with the specified id. Doing so raises
	// an exception if the section is not found, which should result
	// in redirecting to the landing page.
	try {
	    $section = $this->table->getSection($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('section', ['action' => 'list']);
	}

	$form = new SectionForm();
	$form->bind($section);
	$form->get('submit')->setAttribute('value', 'Modifier');

	$request = $this->getRequest();
	$viewData = ['id_Section' => $id, 'form' => $form];

	if (!$request->isPost()) {
	    return $viewData;
	}

	$form->setInputFilter($section->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return $viewData;
	}

	$this->table->saveSection($section);

	return $this->redirect()->toRoute('section', ['action' => 'list']);
    }

    public function archiveAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	//return if no id provided
	if (!$id) {
	    return $this->redirect()->toRoute('section', ['action' => 'list']);
	}
	//return if no object with provided identifier
	try {
	    $section = $this->table->getSection($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('section', ['action' => 'list']);
	}

	$request = $this->getRequest();
	if ($request->isPost()) {
	    $del = $request->getPost('del', 'Non');

	    if ($del == 'Oui') {
		$id = (int) $request->getPost('id');
		$this->table->archiveSection($id);
	    }

	    // Redirect to list of albums
	    return $this->redirect()->toRoute('section',['action'=>'list']);
	}
	
	return [
            'id'    => $id,
            'section' => $this->table->getSection($id),
        ];
    }

}
