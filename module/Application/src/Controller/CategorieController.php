<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\CategorieTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\CategorieForm;
use Application\Model\Categorie;

class CategorieController extends AbstractActionController {

    private $table;

    public function __construct(CategorieTable $table) {
	$this->table = $table;
    }

    public function indexAction() {
	return new ViewModel();
    }

    public function listAction() {
	return new ViewModel([
	    'categories' => $this->table->fetchAll(),
	]);
    }

    public function addAction() {
	$form = new CategorieForm();
	$form->get('submit')->setValue('Créer');

	$request = $this->getRequest();

	if (!$request->isPost()) {
	    return['form' => $form];
	}

	$categorie = new Categorie();
	$form->setInputFilter($categorie->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return['form' => $form];
	}

	$categorie->exchangeArray($form->getData());
	$this->table->saveCategorie($categorie);
	return $this->redirect()->toRoute('categorie', ['action' => 'list']);
    }

    public function editAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	if (0 === $id) {
	    return $this->redirect()->toRoute('categorie', ['action' => 'add']);
	}

	// Retrieve the categorie with the specified id. Doing so raises
	// an exception if the categorie is not found, which should result
	// in redirecting to the landing page.
	try {
	    $categorie = $this->table->getCategorie($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('categorie', ['action' => 'list']);
	}

	$form = new CategorieForm();
	$form->bind($categorie);
	$form->get('submit')->setAttribute('value', 'Modifier');

	$request = $this->getRequest();
	$viewData = ['id_Categorie' => $id, 'form' => $form];

	if (!$request->isPost()) {
	    return $viewData;
	}

	$form->setInputFilter($categorie->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return $viewData;
	}

	$this->table->saveCategorie($categorie);

	return $this->redirect()->toRoute('categorie', ['action' => 'list']);
    }

    public function archiveAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	//return if no id provided
	if (!$id) {
	    return $this->redirect()->toRoute('categorie', ['action' => 'list']);
	}
	//return if no object with provided identifier
	try {
	    $categorie = $this->table->getCategorie($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('categorie', ['action' => 'list']);
	}

	$request = $this->getRequest();
	if ($request->isPost()) {
	    $del = $request->getPost('del', 'Non');

	    if ($del == 'Oui') {
		$id = (int) $request->getPost('id');
		$this->table->archiveCategorie($id);
	    }

	    // Redirect to list of albums
	    return $this->redirect()->toRoute('categorie',['action'=>'list']);
	}
	
	return [
            'id'    => $id,
            'categorie' => $this->table->getCategorie($id),
        ];
    }

}
