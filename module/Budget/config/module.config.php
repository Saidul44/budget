<?php 
$routes = array(		
	'transaction' => array(
		'type' => 'segment',
		'options' => array(
			'route' => '/transaction[/:action]',
			'constraints' => array('action' => '[A-Z_a-z]*'),
			'defaults' => array(
				'controller' => 'Budget\Controller\Transaction',
				'action' => 'list',
				'year' => date('o'),
				'week' => date('W')
			)
		)		
	),
		
	'transaction_list' => array(
		'type' => 'segment',
		'options' => array(
			'route' => '/transaction/list[/week/:week[/year/:year]]',
			'constraints' => array('week' => '[0-9]+'),
			'defaults' => array(
			'controller' => 'Budget\Controller\Transaction',
				'action' => 'list',
				'year' => date('o'),
				'week' => date('W')
			)
		)
	),		

	'transaction_delete' => array(
		'type' => 'segment',
		'options' => array(
			'route' => '/transaction/delete/id/:id',
			'constraints' => array('id' => '[0-9]+'),
			'defaults' => array(
				'controller' => 'Budget\Controller\Transaction',
				'action' => 'delete',
			)
		)
	),
		
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