<?php
	class Dispatcher
	{
		function loadController($controller)
		{
			global $baseDirectory;
			
			$controllerName = ucfirst($controller) . 'Controller';
			
			/* Try to identify controller file */
			$controlerDirname = $baseDirectory . DIRECTORY_SEPARATOR . 'src/controllers';
			$controllerDir = opendir($controlerDirname);
				
			$expected = $controllerName . '.php';
			$controllerFile = NULL;
			while($entry = readdir($controllerDir)) {
				if($entry == $expected) {
					$controllerFile = $entry;
					break;
				}
			}

			if($controllerFile == NULL)
				throw new Exception('Could not locate controller.');
			
			/* Import controller file */
			require_once($controlerDirname . DIRECTORY_SEPARATOR . $controllerFile);
			
			/* Construct controller */
			if(!class_exists($controllerName))
				throw new Exception('Controller class does not exist in file.');
			
			return new $controllerName();
		}
		
		function dispatch()
		{
			/* Construct controller */
			if(array_key_exists('controller', $_GET)) {
				$controller = $_GET['controller'];
			} else {
				$controller = 'default';
			}
			
			$controller = $this->loadController($controller);
			
			/* Invoke action */
			if(array_key_exists('action', $_GET)) {
				$action = $_GET['action'] . 'Action';
			} else {
				$action = 'indexAction';
			}
			
			if(!method_Exists($controller, $action))
				throw new Exception('Action does not exist in controller.');
			
			return $controller->$action();
		}
	}
