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
			return $webRoot . '/' . $resource;
		}

		function renderResourceLink($resource)
		{
			echo(htmlentities($this->linkToResource($resource)));
		}
		
		function linkTo($controller, $action = 'index', $values = array())
		{
			global $webRoot;
			
			$tmp = $webRoot . '/index.php?controller=' . $controller . '&action=' . $action;
			
			foreach($values as $key => $value) {
				$tmp .= '&';
				$tmp .= $key;
				$tmp .= '=';
				$tmp .= $value;
			}
			
			return $tmp;
		}		
		
		function renderLink($controller, $action = 'index', $values = array())
		{
			echo(htmlentities($this->linkTo($controller, $action, $values)));
		}
		
	}
