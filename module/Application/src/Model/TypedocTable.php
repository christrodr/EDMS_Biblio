<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Application\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Mvc\Plugin\FlashMessenger\FlashMessenger;

class TypedocTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
	$this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
	return $this->tableGateway->select(['archiver' => 'non']);
    }

    public function getTypedoc($id) {
	$id = (int) $id;
	$rowset = $this->tableGateway->select(['id_Typedoc' => $id]);
	$row = $rowset->current();
	if (!$row) {
	    throw new RuntimeException(sprintf(
		    'Impossible de trouver un type de document avec cet identifiant %d', $id
	    ));
	}

	return $row;
    }

    public function getByNameTypedoc($nom) {
	$rowset = $this->tableGateway->select(['nom' => $nom]);
	$row = $rowset->current();
	if ($row) {
	    return $row;
	}
    }

    public function saveTypedoc(Typedoc $typedoc) {
	$data = [
	    'nom' => $typedoc->nom,
	    'archiver' => 'non',
	];

	$id = (int) $typedoc->id;

	if ($id === 0) {
	    $existTypedoc = $this->getByNameTypedoc($typedoc->nom);
	    if (!$existTypedoc) {
		$this->tableGateway->insert($data);
		$message = new FlashMessenger();
		$message->addSuccessMessage('Le type de document "' . $data['nom'] . '" à été créé.');
	    } else {
		if ($existTypedoc->archiver == 'oui') {
		    $this->unarchiveTypedoc($existTypedoc->id);
		} else {
		    $message = new FlashMessenger();
		    $message->addErrorMessage('Le type de document "' . $data['nom'] . '" existe déjà.');
		}
	    }

	    return;
	}

	if (!$this->getTypedoc($id)) {
	    throw new RuntimeException(sprintf(
		    'Le type de document avec cet identifiant %d; n\'existe pas', $id
	    ));
	}

	if (!$this->getByNameTypedoc($typedoc->nom)) {
	    //recuperation de l'ancien nom
	    $oldtypedoc = $this->getTypedoc($id);
	    $oldname = $oldtypedoc->nom;

	    $this->tableGateway->update($data, ['id_Typedoc' => $id]);
	    $message = new FlashMessenger();
	    $message->addSuccessMessage('Le type de document "' . $oldname . '" à été modifié en "' . $data['nom'] . '".');
	} else {
	    $message = new FlashMessenger();
	    $message->addErrorMessage('Le type de document "' . $data['nom'] . '" existe déjà.');
	}
    }

    public function archiveTypedoc($id) {

	if (!$this->getTypedoc($id)) {
	    throw new RuntimeException(sprintf(
		    'Le type de document avec cet identifiant %d; n\'existe pas', $id
	    ));
	}
	$message = new FlashMessenger();
	$message->addErrorMessage('Le type de document "' . $this->getTypedoc($id)->nom . '" a été archivé.');
	$this->tableGateway->update(['archiver' => 'oui'], ['id_Typedoc' => $id]);
    }
    
    public function unarchiveTypedoc($id) {

	if (!$this->getTypedoc($id)) {
	    throw new RuntimeException(sprintf(
		    'Le type de document avec cet identifiant %d; n\'existe pas', $id
	    ));
	}
	$message = new FlashMessenger();
	$message->addSuccessMessage('Le type de document "' . $this->getTypedoc($id)->nom . '" a été désarchivé.');
	$this->tableGateway->update(['archiver' => 'non'], ['id_Typedoc' => $id]);
    }

}
