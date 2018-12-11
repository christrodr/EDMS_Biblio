<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface {

    public function getConfig() {
	return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
	return [
	    'factories' => [
		Model\UtilisateurTable::class => function($container) {
		    $tableGateway = $container->get(Model\UtilisateurTableGateway::class);
		    return new Model\UtilisateurTable($tableGateway);
		},
		Model\UtilisateurTableGateway::class => function ($container) {
		    $dbAdapter = $container->get(AdapterInterface::class);
		    $resultSetPrototype = new ResultSet();
		    $resultSetPrototype->setArrayObjectPrototype(new Model\Utilisateur());
		    return new TableGateway('utilisateur', $dbAdapter, null, $resultSetPrototype);
		},
	    ],
	];
    }

    public function getControllerConfig() {
	return [
	    'factories' => [
		Controller\IndexController::class => function($container) {
		    return new Controller\IndexController(
			    $container->get(Model\UtilisateurTable::class)
		    );
		},
	    ],
	];
    }

}
