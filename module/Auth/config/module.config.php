<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Auth;

//use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
	'routes' => [
	    'auth' => [
		'type' => Segment::class,
		'options' => [
		    'route' => '/auth[/:action[/:id]]',
		    'constraints' => [
			'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
			'id' => '[0-9]+',
		    ],
		    'defaults' => [
			'controller' => Controller\IndexController::class,
			'action' => 'index',
		    ],
		],
	    ],
	],
    ],
    'view_manager' => [
	'template_path_stack' => [
	    'auth' => __DIR__ . '/../view',
	],
    ],
];
