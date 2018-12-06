<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Model\AuteurTable;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\AuteurForm;
use Application\Model\Auteur;

class AuteurController extends AbstractActionController {

    private $table;

    public function __construct(AuteurTable $table) {
	$this->table = $table;
    }

    public function indexAction() {
	return new ViewModel();
    }

    public function listAction() {
	return new ViewModel([
	    'auteurs' => $this->table->fetchAll(),
	]);
    }

    public function addAction() {
	$form = new AuteurForm();
	$form->get('submit')->setValue('Créer');

	$request = $this->getRequest();

	if (!$request->isPost()) {
	    return['form' => $form];
	}

	$auteur = new Auteur();
	$form->setInputFilter($auteur->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return['form' => $form];
	}

	$auteur->exchangeArray($form->getData());
	$this->table->saveAuteur($auteur);
	return $this->redirect()->toRoute('auteur', ['action' => 'list']);
    }

    public function editAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	if (0 === $id) {
	    return $this->redirect()->toRoute('auteur', ['action' => 'add']);
	}

	// Retrieve the auteur with the specified id. Doing so raises
	// an exception if the auteur is not found, which should result
	// in redirecting to the landing page.
	try {
	    $auteur = $this->table->getAuteur($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('auteur', ['action' => 'list']);
	}

	$form = new AuteurForm();
	$form->bind($auteur);
	$form->get('submit')->setAttribute('value', 'Modifier');

	$request = $this->getRequest();
	$viewData = ['id_Auteur' => $id, 'form' => $form];

	if (!$request->isPost()) {
	    return $viewData;
	}

	$form->setInputFilter($auteur->getInputFilter());
	$form->setData($request->getPost());

	if (!$form->isValid()) {
	    return $viewData;
	}

	$this->table->saveAuteur($auteur);

	return $this->redirect()->toRoute('auteur', ['action' => 'list']);
    }

    public function archiveAction() {
	$id = (int) $this->params()->fromRoute('id', 0);
	//return if no id provided
	if (!$id) {
	    return $this->redirect()->toRoute('auteur', ['action' => 'list']);
	}
	//return if no object with provided identifier
	try {
	    $auteur = $this->table->getAuteur($id);
	} catch (\Exception $e) {
	    return $this->redirect()->toRoute('auteur', ['action' => 'list']);
	}

	$request = $this->getRequest();
	if ($request->isPost()) {
	    $del = $request->getPost('del', 'Non');

	    if ($del == 'Oui') {
		$id = (int) $request->getPost('id');
		$this->table->archiveAuteur($id);
	    }

	    // Redirect to list of albums
	    return $this->redirect()->toRoute('auteur',['action'=>'list']);
	}
	
	return [
            'id'    => $id,
            'auteur' => $this->table->getAuteur($id),
        ];
    }

}
