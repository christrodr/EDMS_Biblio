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

class CategorieTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
	$this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
	return $this->tableGateway->select(['archiver' => 'non']);
    }

    public function getCategorie($id) {
	$id = (int) $id;
	$rowset = $this->tableGateway->select(['id_Categorie' => $id]);
	$row = $rowset->current();
	if (!$row) {
	    throw new RuntimeException(sprintf(
		    'Impossible de trouver une categorie avec cet identifiant %d', $id
	    ));
	}

	return $row;
    }

    public function getByNameCategorie($nom) {
	$rowset = $this->tableGateway->select(['nom' => $nom]);
	$row = $rowset->current();
	if ($row) {
	    return $row;
	}
    }

    public function saveCategorie(Categorie $categorie) {
	$data = [
	    'nom' => $categorie->nom,
	    'archiver' => 'non',
	];

	$id = (int) $categorie->id;

	if ($id === 0) {
	    if (!$this->getByNameCategorie($categorie->nom)) {
		$this->tableGateway->insert($data);
		$message = new FlashMessenger();
		$message->addSuccessMessage('La catégorie "' . $data['nom'] . '" à été créée.');
	    } else {
//		$this->tableGateway->insert($data);
		$message = new FlashMessenger();
		$message->addErrorMessage('La catégorie "' . $data['nom'] . '" existe déjà.');
	    }

	    return;
	}

	if (!$this->getCategorie($id)) {
	    throw new RuntimeException(sprintf(
		    'La catégorie avec cet identifiant %d; n\'existe pas', $id
	    ));
	}

	if (!$this->getByNameCategorie($categorie->nom)) {
	    //recuperation de l'ancien nom
	    $oldcategorie = $this->getCategorie($id);
	    $oldname = $oldcategorie->nom;

	    $this->tableGateway->update($data, ['id_Categorie' => $id]);
	    $message = new FlashMessenger();
	    $message->addSuccessMessage('La catégorie "' . $oldname . '" à été modifiée en "' . $data['nom'] . '".');
	} else {
	    $message = new FlashMessenger();
	    $message->addErrorMessage('La catégorie "' . $data['nom'] . '" existe déjà.');
	}
    }

    public function archiveCategorie($id) {

	if (!$this->getCategorie($id)) {
	    throw new RuntimeException(sprintf(
		    'La catégorie avec cet identifiant %d; n\'existe pas', $id
	    ));
	}
	$message = new FlashMessenger();
	$message->addErrorMessage('La catégorie "' . $this->getCategorie($id)->nom . '" a été archivée.');
	$this->tableGateway->update(['archiver' => 'oui'], ['id_Categorie' => $id]);
    }

}
