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
	return $this->tableGateway->select();
    }

    public function getSection($id) {
	$id = (int) $id;
	$rowset = $this->tableGateway->select(['id_Categorie' => $id]);
	$row = $rowset->current();
	if (!$row) {
	    throw new RuntimeException(sprintf(
		    'Impossible de trouver une section avec cet identifiant %d', $id
	    ));
	}

	return $row;
    }

    public function saveSection(Section $section) {
	$data = [
	    'nom' => $section->nom,
	    'archiver' => 'non',
	];

	$id = (int) $section->id;

	if ($id === 0) {
	    $this->tableGateway->insert($data);
	    $message=new FlashMessenger();
	    $message->addSuccessMessage('OK');
	    return;
	}

	if (!$this->getSection($id)) {
	    throw new RuntimeException(sprintf(
		    'La section avec cet identifiant %d; n\'existe pas', $id
	    ));
	}

	$this->tableGateway->update($data, ['id_Section' => $id]);
    }

    public function archiveSection($id) {
	$id = (int) $section->id;
	
	if (!$this->getSection($id)) {
	    throw new RuntimeException(sprintf(
		    'La section avec cet identifiant %d; n\'existe pas', $id
	    ));
	}
	
	$this->tableGateway->update(['archiver'=>'oui'], ['id_Section' => $id]);
	
    }

}
