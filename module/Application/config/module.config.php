<?php

/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

//use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
	'routes' => [
	    'home' => [
		'type' => Literal::class,
		'options' => [
		    'route' => '/',
		    'defaults' => [
			'controller' => Controller\IndexController::class,
			'action' => 'index',
		    ],
		],
	    ],
	    'application' => [
		'type' => Segment::class,
		'options' => [
		    'route' => '/application[/:action]',
		    'defaults' => [
			'controller' => Controller\IndexController::class,
			'action' => 'index',
		    ],
		],
	    ],
	    'section' => [
		'type' => Segment::class,
		'options' => [
		    'route' => '/section[/:action[/:id]]',
		    'constraints' => [
			'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
			'id' => '[0-9]+',
		    ],
		    'defaults' => [
			'controller' => Controller\SectionController::class,
			'action' => 'index',
		    ],
		],
	    ],
	    'categorie' => [
		'type' => Segment::class,
		'options' => [
		    'route' => '/categorie[/:action[/:id]]',
		    'constraints' => [
			'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
			'id' => '[0-9]+',
		    ],
		    'defaults' => [
			'controller' => Controller\CategorieController::class,
			'action' => 'index',
		    ],
		],
	    ],
	],
    ],
//    'controllers' => [
//        'factories' => [
//            Controller\IndexController::class => InvokableFactory::class,
////            Controller\SectionController::class => InvokableFactory::class,
//        ],
//    ],
    'view_manager' => [
	'display_not_found_reason' => true,
	'display_exceptions' => true,
	'doctype' => 'HTML5',
	'not_found_template' => 'error/404',
	'exception_template' => 'error/index',
	'template_map' => [
	    'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
	    'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
	    'error/404' => __DIR__ . '/../view/error/404.phtml',
	    'error/index' => __DIR__ . '/../view/error/index.phtml',
	],
	'template_path_stack' => [
	    __DIR__ . '/../view',
	],
    ],
    'view_helper_config' => [
	'flashmessenger' => [
	    'message_open_format' => '<div%s><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><ul><li>',
	    'message_close_string' => '</li></ul></div>',
	    'message_separator_string' => '</li><li>',
	],
    ],
    'navigation' => [
	'default' => [
	    [
		'label' => 'Sections',
		'route' => 'section',
		'action' => 'list',
		'pages' => [
		    [
			'label' => 'Accueil section',
			'route' => 'section',
			'action' => 'index',
		    ],
		    [
			'label' => 'Lister',
			'route' => 'section',
			'action' => 'list',
		    ],
		    [
			'label' => 'Editer',
			'route' => 'section',
			'action' => 'edit',
		    ],
		    [
			'label' => 'Archiver',
			'route' => 'section',
			'action' => 'archive',
		    ],
		],
	    ],
	    [
		'label' => 'Catégories',
		'route' => 'categorie',
		'action' => 'list',
		'pages' => [
		    [
			'label' => 'Accueil categorie',
			'route' => 'categorie',
			'action' => 'index',
		    ],
		    [
			'label' => 'Lister',
			'route' => 'categorie',
			'action' => 'list',
		    ],
		    [
			'label' => 'Editer',
			'route' => 'categorie',
			'action' => 'edit',
		    ],
		    [
			'label' => 'Archiver',
			'route' => 'categorie',
			'action' => 'archive',
		    ],
		],
	    ],
	],
    ],
];
