<?php 
	$baseDirectory = realpath('/home/ivarclemens/public_html/budget');
	set_include_path(get_include_path() . PATH_SEPARATOR . $baseDirectory . DIRECTORY_SEPARATOR . 'src');
	
	$webRoot = '/budget/www';
	
	$debug = 1;
	
	if($debug) {
		ini_set('display_errors', 1);
		error_reporting(~0);
	}
	
	/* Connect to the database */
	require_once('config.php');
	$database = new PDO($config['dbtype'] . 
			':host=' . $config['hostname'] . 
			';dbname=' . $config['database'],
			$config['username'], $config['password']);

	/* Dispatch request */
	require_once('Dispatcher.php');		
	$dispatcher = new Dispatcher();
	$payload = $dispatcher->dispatch();
	
	echo($payload);
