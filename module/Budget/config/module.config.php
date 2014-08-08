<?php 
$routes = array(
	'transaction' => array(
		'type' => 'segment',
		'options' => array(
			'route' => '/transaction[/:action]',
			'constraints' => array('action' => '[A-Za-z]*'),
			'defaults' => array(
				'controller' => 'Budget\Controller\Transaction',
				'action' => 'index'
			)
		)		
	)
);

return array(
	'router' => array(
		'routes' => $routes
	),
	
	'controllers' => array(
		'invokables' => array(
			'Budget\Controller\Index' => 'Budget\Controller\IndexController',
			'Budget\Controller\Transaction' => 'Budget\Controller\TransactionController'
		)
	),
		
	'view_manager' => array(
		'display_not_found_reason' => true,
		'display_exceptions' => true,
		
		'doctype' => 'HTML5',
			
		'not_found_template' => 'error/not_found',
		'exception_template' => 'error/exception',
			
		'template_map' => array(
			'error/index' => __DIR__ . '/../view/error/index.phtml',
		),
		
		'template_path_stack' => array(
			__DIR__ . '/../view',
		)
	)
);