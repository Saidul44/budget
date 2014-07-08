<?php
	require_once('Controller.php');
	require_once('models/Categories.php');
	
	class CategoryController extends Controller
	{
		function listAction() {
			$categories = Categories::getCategories();
			
			$this->setFragmentValue('title', 'Categories');
			$this->setFragment('main-area', 'category_list.php', array('categories' => $categories));
			
			return $this->renderPage('base.php');
		}
		
		function newAction() {
			$categories = Categories::getCategories();			
			$root_category = Categories::getRootCategory();
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {
				$category = new Category(array(
					'name' => $_POST['name'],
					'parent_id' => intval($_POST['parent_id'])
				));
				
				Categories::save($category);
				
				return $this->redirect('category', 'list');
			} else {			
				$this->setFragmentValue('title', 'Add category');
				$this->setFragment('main-area', 'category_form.php', 
						array('categories' => $categories, 'root' => $root_category));
			
				return $this->renderPage('base.php');
			}
		}
		
		function editAction() {
			$categories = Categories::getCategories();
			$root_category = Categories::getRootCategory();
			
			$id = intval($_GET['id']);
			$category = Categories::getCategoryById($id);
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {				
				$category->setName($_POST['name']);
				$category->setParentById($_POST['parent_id']);
				
				Categories::save($category);
				return $this->redirect('category', 'list');
			} else {
				$this->setFragmentValue('title', 'Edit category');
				$this->setFragment('main-area', 'category_form.php',
						array('categories' => $categories, 'root' => $root_category, 'category' => $category));

				return $this->renderPage('base.php');
			}
		}
		
		function deleteAction() {
			$category_id = intval($_POST['category_id']);
			$category = Categories::getCategoryById($category_id);
			
			Categories::delete($category);
			
			return $this->redirect('category', 'list');
		}
	}
