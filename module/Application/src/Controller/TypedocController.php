<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\TypedocTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\TypedocForm;
use Application\Model\Typedoc;

class TypedocController extends AbstractActionController {

    private $table;

    public function __construct(TypedocTable $table) {
	$this->table = $table;
    }

    public function indexAction() {
	return new ViewModel();
    }

    public function listAction() {
	return new ViewModel([
	    'typedocs' => $this->table->fetchAll(),
	]);
    }

    public function addAction() {
	$form = new TypedocForm();
	$form->get('submit')->setValue('Créer');

	$request = $this->getRequest();

	if (!$request->isPost()) {
	    return['form' => $form];
	}

	$typedoc = new Typedoc();
	$form->setInputFilter($typedoc->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return['form' => $form];
	}

	$typedoc->exchangeArray($form->getData());
	$this->table->saveTypedoc($typedoc);
	return $this->redirect()->toRoute('typedoc', ['action' => 'list']);
    }

    public function editAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	if (0 === $id) {
	    return $this->redirect()->toRoute('typedoc', ['action' => 'add']);
	}

	// Retrieve the typedoc with the specified id. Doing so raises
	// an exception if the typedoc is not found, which should result
	// in redirecting to the landing page.
	try {
	    $typedoc = $this->table->getTypedoc($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('typedoc', ['action' => 'list']);
	}

	$form = new TypedocForm();
	$form->bind($typedoc);
	$form->get('submit')->setAttribute('value', 'Modifier');

	$request = $this->getRequest();
	$viewData = ['id_Typedoc' => $id, 'form' => $form];

	if (!$request->isPost()) {
	    return $viewData;
	}

	$form->setInputFilter($typedoc->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return $viewData;
	}

	$this->table->saveTypedoc($typedoc);

	return $this->redirect()->toRoute('typedoc', ['action' => 'list']);
    }

    public function archiveAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	//return if no id provided
	if (!$id) {
	    return $this->redirect()->toRoute('typedoc', ['action' => 'list']);
	}
	//return if no object with provided identifier
	try {
	    $typedoc = $this->table->getTypedoc($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('typedoc', ['action' => 'list']);
	}

	$request = $this->getRequest();
	if ($request->isPost()) {
	    $del = $request->getPost('del', 'Non');

	    if ($del == 'Oui') {
		$id = (int) $request->getPost('id');
		$this->table->archiveTypedoc($id);
	    }

	    // Redirect to list of albums
	    return $this->redirect()->toRoute('typedoc',['action'=>'list']);
	}
	
	return [
            'id'    => $id,
            'typedoc' => $this->table->getTypedoc($id),
        ];
    }

}
