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

class SectionTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
	$this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
	return $this->tableGateway->select(['archiver' => 'non']);
    }

    public function getSection($id) {
	$id = (int) $id;
	$rowset = $this->tableGateway->select(['id_Section' => $id]);
	$row = $rowset->current();
	if (!$row) {
	    throw new RuntimeException(sprintf(
		    'Impossible de trouver une section avec cet identifiant %d', $id
	    ));
	}

	return $row;
    }

    public function getByNameSection($nom) {
	$rowset = $this->tableGateway->select(['nom' => $nom]);
	$row = $rowset->current();
	if ($row) {
	    return $row;
	}
    }

    public function saveSection(Section $section) {
	$data = [
	    'nom' => $section->nom,
	    'archiver' => 'non',
	];

	$id = (int) $section->id;

	if ($id === 0) {
	    if (!$this->getByNameSection($section->nom)) {
		$this->tableGateway->insert($data);
		$message = new FlashMessenger();
		$message->addSuccessMessage('La section "' . $data['nom'] . '" à été créée.');
	    } else {
//		$this->tableGateway->insert($data);
		$message = new FlashMessenger();
		$message->addErrorMessage('La section "' . $data['nom'] . '" existe déjà.');
	    }

	    return;
	}

	if (!$this->getSection($id)) {
	    throw new RuntimeException(sprintf(
		    'La section avec cet identifiant %d; n\'existe pas', $id
	    ));
	}

	if (!$this->getByNameSection($section->nom)) {
	    //recuperation de l'ancien nom
	    $oldsection = $this->getSection($id);
	    $oldname = $oldsection->nom;

	    $this->tableGateway->update($data, ['id_Section' => $id]);
	    $message = new FlashMessenger();
	    $message->addSuccessMessage('La section "' . $oldname . '" à été modifiée en "' . $data['nom'] . '".');
	} else {
	    $message = new FlashMessenger();
	    $message->addErrorMessage('La section "' . $data['nom'] . '" existe déjà.');
	}
    }

    public function archiveSection($id) {

	if (!$this->getSection($id)) {
	    throw new RuntimeException(sprintf(
		    'La section avec cet identifiant %d; n\'existe pas', $id
	    ));
	}
	$message = new FlashMessenger();
	$message->addErrorMessage('La section "' . $this->getSection($id)->nom . '" a été archivée.');
	$this->tableGateway->update(['archiver' => 'oui'], ['id_Section' => $id]);
    }

}
