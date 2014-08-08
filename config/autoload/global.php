<?php 
return array(
	'db' => array(
		'driver' => 'Mysqli',
   		'hostname' => 'localhost',
   		'database' => 'ivar_budget'),
	
	'service_manager' => array(
		'factories' => array(
			'Zend\Db\Adapter\Adapter' =>
				'Zend\Db\Adapter\AdapterServiceFactory',
		)
	)
);
	