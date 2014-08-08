<?php 
return array(
	'db' => array(
		'driver' => 'Pdo',
		'dsn'    => 'mysql:dbname=ivar_budget;host=localhost',
   		'hostname' => 'localhost',
   		'database' => 'ivar_budget'
	),
	
	'service_manager' => array(
		'factories' => array(
			'Zend\Db\Adapter\Adapter' =>
				'Zend\Db\Adapter\AdapterServiceFactory',				
		)
	)
);
	