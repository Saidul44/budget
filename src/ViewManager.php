<?php 
	class ViewManager
	{
		function __construct() {
			$this->fragments = array();
		}

		function setFragment($name, $value)
		{
			$this->fragments[$name] = $value;
		}
		
		function renderFragment($name)
		{
			if(array_key_exists($name, $this->fragments)) {
				echo($this->fragments[$name]);
			} else {
				echo('##MISSING FRAGMENT: ' . $name . '##');
			}
		}
		
		function linkToResource($resource)
		{
			global $webRoot;
			echo(htmlentities($webRoot . '/' . $resource));
		}		
		
		function linkTo($controller, $action = 'index', $values = array())
		{
			global $webRoot;
			echo(htmlentities($webRoot . '/index.php?controller=' . $controller . '&action=' . $action));
		}		
		
	}
