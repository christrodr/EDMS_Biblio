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

class AuteurTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
	$this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
	return $this->tableGateway->select(['archiver' => 'non']);
    }

    public function getAuteur($id) {
	$id = (int) $id;
	$rowset = $this->tableGateway->select(['id_Auteur' => $id]);
	$row = $rowset->current();
	if (!$row) {
	    throw new RuntimeException(sprintf(
		    'Impossible de trouver un auteur avec cet identifiant %d', $id
	    ));
	}

	return $row;
    }

    public function getByNameAuteur($nom) {
	$rowset = $this->tableGateway->select(['nom' => $nom]);
	$row = $rowset->current();
	if ($row) {
	    return $row;
	}
    }

    public function saveAuteur(Auteur $auteur) {
	$data = [
	    'nom' => $auteur->nom,
	    'archiver' => 'non',
	];

	$id = (int) $auteur->id;

	if ($id === 0) {
	    $existAuteur = $this->getByNameAuteur($auteur->nom);
	    if (!$existAuteur) {
		$this->tableGateway->insert($data);
		$message = new FlashMessenger();
		$message->addSuccessMessage('L\'auteur "' . $data['nom'] . '" à été créé.');
	    } else {
		if ($existAuteur->archiver == 'oui') {
		    $this->unarchiveAuteur($existAuteur->id);
		} else {
		    $message = new FlashMessenger();
		    $message->addErrorMessage('L\'auteur "' . $data['nom'] . '" existe déjà.');
		}
	    }

	    return;
	}

	if (!$this->getAuteur($id)) {
	    throw new RuntimeException(sprintf(
		    'L\'auteur avec cet identifiant %d; n\'existe pas', $id
	    ));
	}

	if (!$this->getByNameAuteur($auteur->nom)) {
	    //recuperation de l'ancien nom
	    $oldauteur = $this->getAuteur($id);
	    $oldname = $oldauteur->nom;

	    $this->tableGateway->update($data, ['id_Auteur' => $id]);
	    $message = new FlashMessenger();
	    $message->addSuccessMessage('L\'auteur "' . $oldname . '" à été modifié en "' . $data['nom'] . '".');
	} else {
	    $message = new FlashMessenger();
	    $message->addErrorMessage('L\'auteur "' . $data['nom'] . '" existe déjà.');
	}
    }

    public function archiveAuteur($id) {

	if (!$this->getAuteur($id)) {
	    throw new RuntimeException(sprintf(
		    'L\'auteur avec cet identifiant %d; n\'existe pas', $id
	    ));
	}
	$message = new FlashMessenger();
	$message->addErrorMessage('L\'auteur "' . $this->getAuteur($id)->nom . '" a été archivé.');
	$this->tableGateway->update(['archiver' => 'oui'], ['id_Auteur' => $id]);
    }
    
    public function unarchiveAuteur($id) {

	if (!$this->getAuteur($id)) {
	    throw new RuntimeException(sprintf(
		    'L\'auteur avec cet identifiant %d; n\'existe pas', $id
	    ));
	}
	$message = new FlashMessenger();
	$message->addSuccessMessage('L\'auteur "' . $this->getAuteur($id)->nom . '" a été désarchivé.');
	$this->tableGateway->update(['archiver' => 'non'], ['id_Auteur' => $id]);
    }

}
