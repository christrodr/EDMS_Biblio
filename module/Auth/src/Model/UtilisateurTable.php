<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth\Model;

use RuntimeException;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Authentication\Result;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;

class UtilisateurTable {

    private $tableGateway;

    public function __construct(TableGatewayInterface $tableGateway) {
	$this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
	return $this->tableGateway->select();
    }

    public function getUser($id) {
	$id = (int) $id;
	$rowset = $this->tableGateway->select(['id_Utilisateur' => $id]);
	$row = $rowset->current();
	if (!$row) {
	    throw new RuntimeException(sprintf(
		    'Could not find row with identifier %d', $id
	    ));
	}

	return $row;
    }

    public function logUser(Utilisateur $utilisateur) {
	$data = [
	    'user' => $utilisateur->user,
	    'pass' => $utilisateur->pass,
	    'rememberme' => $utilisateur->rememberme,
	];

	$dbAdapter = $this->tableGateway->adapter;
	$authAdapter = new AuthAdapter($dbAdapter, 'utilisateur', 'login', 'pass', 'SHA1(?)');
	$authAdapter->setIdentity($data['user']);
	$authAdapter->setCredential($data['pass']);

	$auth = new AuthenticationService();

	$result = $auth->authenticate($authAdapter);

	switch ($result->getCode()) {
	    case Result::FAILURE_IDENTITY_NOT_FOUND:
		// do stuff for nonexistent identity
		echo 'FAILURE_IDENTITY_NOT_FOUND';
		break;

	    case Result::FAILURE_CREDENTIAL_INVALID:
		// do stuff for invalid credential
		echo 'FAILURE_CREDENTIAL_INVALID';
		break;

	    case Result::SUCCESS:
		$storage = $auth->getStorage();
		$storage->write($authAdapter->getResultRowObject(
				null, 'pass'
		));
		echo 'LOGGED IN';
		$time = 1209600; // 14 days 1209600/3600 = 336 hours => 336/24 = 14 days
////						if ($data['rememberme']) $storage->getSession()->getManager()->rememberMe($time); // no way to get the session
		if ($data['rememberme']) {
		    $sessionManager = new \Zend\Session\SessionManager();
		    $sessionManager->rememberMe($time);
		}
		break;

	    default:
		// do stuff for other failure
		echo 'LOSE';
		break;
	}
    }

}
