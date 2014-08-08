<?php
	/**
	 * Budget Applications
	 * 
	 * @copyright Copyright (c) 2014 Ivar Clemens
	 */

	$debug = 1;
	
	if($debug) {
		error_reporting(E_ALL);
		ini_set('display_errors', true);
	}
	
	// Set time zone (should use value from config instead
	date_default_timezone_set('Europe/Lisbon');
	
	// Change directory to application root 
	chdir(dirname(__DIR__));

	
	// Instantiate auto loader
	if(!file_exists('vendor/Zend/Loader/AutoloaderFactory.php'))
		show_error('Could not find autoloader.');
		
	include 'vendor/Zend/Loader/AutoloaderFactory.php';
	
	if(!class_exists('Zend\Loader\AutoloaderFactory'))
		show_error('Could not load autoloader.');
	
	Zend\Loader\AutoloaderFactory::factory(array(
		'Zend\Loader\StandardAutoloader' => array(
		'autoregister_zf' => true)));

	
	// Code that still needs to be updated
	$baseDirectory = realpath('/home/ivarclemens/applications/budget');
	set_include_path(get_include_path() . PATH_SEPARATOR . $baseDirectory . DIRECTORY_SEPARATOR . 'src');
	
	$webRoot = '/budget';
	
	$debug = 1;
	
	if($debug) {
		ini_set('display_errors', 1);
		error_reporting(~0);
	}

	
	// Setup database
	$global = require 'config/autoload/global.php';
	$local = require 'config/autoload/local.php';
	
	/* Connect to the database */
	$database = new PDO('mysql' . 
			':host=' . $global['db']['hostname'] . 
			';dbname=' . $global['db']['database'],
			$local['db']['username'], $local['db']['password']);

	
	// Start application
	Zend\Mvc\Application::init(require 'config/application.config.php')->run();

	
	function show_error($message)
	{
		echo($message);
		exit(1);
	}
	