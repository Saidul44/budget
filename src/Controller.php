<?php
	require_once('ViewManager.php');
	
	class Controller
	{
		function __construct()
		{
			$this->viewManager = new ViewManager();
		}
		
		function setFragment($name, $file, $options = array())
		{
			$this->viewManager->setFragment($name,
					$this->renderPage($file, $options));
		}
		
		function setFragmentValue($name, $value)
		{
			$this->viewManager->setFragment($name, $value);
		}
		
		function renderPage($name, $options = array())
		{
			$viewManager = $this->viewManager;
			
			ob_start();
			require('views' . DIRECTORY_SEPARATOR . $name);
			return ob_get_clean();
		}
		
		function redirect($controller, $action, $options = array())
		{
			header('Location: ' . $this->viewManager->linkTo($controller, $action, $options));
		}
	}