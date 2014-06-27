<?php
	require_once('Controller.php');
	require_once('models/Categories.php');
	
	class CategoryController extends Controller
	{
		function listAction() {
			$categories = Categories::getCategories();
			
			$this->setFragmentValue('title', 'Categories');
			$this->setFragment('main-area', 'categories.php', array('categories' => $categories));
			
			return $this->renderPage('base.php');
		}
	}
