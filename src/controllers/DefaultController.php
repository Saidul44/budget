<?php
	require_once('Controller.php');
	
	class DefaultController extends Controller
	{
		function indexAction() 
		{
			$this->setFragmentValue('title', 'Dashboard');
			//$this->setFragment('main-area', 'views/categories.php');
			
			return $this->renderPage('base.php');
		}
	}
