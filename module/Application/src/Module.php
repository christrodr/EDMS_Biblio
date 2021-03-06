<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface {

    const VERSION = '3.0.3-dev';

    public function getConfig() {
	return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig() {
	return [
	    'factories' => [
		Model\SectionTable::class => function($container) {
		    $tableGateway = $container->get(Model\SectionTableGateway::class);
		    return new Model\SectionTable($tableGateway);
		},
		Model\SectionTableGateway::class => function ($container) {
		    $dbAdapter = $container->get(AdapterInterface::class);
		    $resultSetPrototype = new ResultSet();
		    $resultSetPrototype->setArrayObjectPrototype(new Model\Section());
		    return new TableGateway('section', $dbAdapter, null, $resultSetPrototype);
		},
		Model\CategorieTable::class => function($container) {
		    $tableGateway = $container->get(Model\CategorieTableGateway::class);
		    return new Model\CategorieTable($tableGateway);
		},
		Model\CategorieTableGateway::class => function ($container) {
		    $dbAdapter = $container->get(AdapterInterface::class);
		    $resultSetPrototype = new ResultSet();
		    $resultSetPrototype->setArrayObjectPrototype(new Model\Categorie());
		    return new TableGateway('categorie', $dbAdapter, null, $resultSetPrototype);
		},
		Model\TypedocTable::class => function($container) {
		    $tableGateway = $container->get(Model\TypedocTableGateway::class);
		    return new Model\TypedocTable($tableGateway);
		},
		Model\TypedocTableGateway::class => function ($container) {
		    $dbAdapter = $container->get(AdapterInterface::class);
		    $resultSetPrototype = new ResultSet();
		    $resultSetPrototype->setArrayObjectPrototype(new Model\Typedoc());
		    return new TableGateway('typedoc', $dbAdapter, null, $resultSetPrototype);
		},
		Model\AuteurTable::class => function($container) {
		    $tableGateway = $container->get(Model\AuteurTableGateway::class);
		    return new Model\AuteurTable($tableGateway);
		},
		Model\AuteurTableGateway::class => function ($container) {
		    $dbAdapter = $container->get(AdapterInterface::class);
		    $resultSetPrototype = new ResultSet();
		    $resultSetPrototype->setArrayObjectPrototype(new Model\Auteur());
		    return new TableGateway('auteur', $dbAdapter, null, $resultSetPrototype);
		},
	    ],
	];
    }

    public function getControllerConfig() {
	return [
	    'factories' => [
		Controller\IndexController::class => function($container) {
		    return new Controller\IndexController();
		},
		Controller\SectionController::class => function($container) {
		    return new Controller\SectionController(
			    $container->get(Model\SectionTable::class)
		    );
		},
		Controller\CategorieController::class => function($container) {
		    return new Controller\CategorieController(
			    $container->get(Model\CategorieTable::class)
		    );
		},
		Controller\TypedocController::class => function($container) {
		    return new Controller\TypedocController(
			    $container->get(Model\TypedocTable::class)
		    );
		},
		Controller\AuteurController::class => function($container) {
		    return new Controller\AuteurController(
			    $container->get(Model\AuteurTable::class)
		    );
		},
	    ],
	];
    }

}
