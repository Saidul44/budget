<?php 
	class Category
	{
		private $id;
		private $name;

		function __construct($fields = array()) {
			if(array_key_exists('id', $fields)) $this->id = $fields['id'];
			if(array_key_exists('parent_id', $fields)) $this->parent_id = $fields['parent_id'];
			if(array_key_exists('name', $fields)) $this->name = $fields['name'];
		}

		function getId() {
			return $this->id;
		}

		function getParent() {
			return Categories::getCategoryById($this->parent_id);
		}
		
		function setParent($parent) {
			$this->parent_id = $parent->getId();
		}
		
		function setName($name) {
			$this->name = $name;
		}

		function getName() {
			return $this->name;
		}
	}

	class Categories
	{
		static function getCategories() {
			global $database;
				
			/* Get categories */
			$stmt = $database->prepare('SELECT c.*, cp.name as parent_name FROM categories as c, categories as cp WHERE c.parent_id = cp.id ORDER BY parent_name, name');
			$stmt->execute();
			$categories = array();
			while($row = $stmt->fetch())
				$categories[] = new Category($row);
			
			return $categories;
		}
		
		static function getCategoryById($id) {
			global $database;
			
			$stmt = $database->prepare('SELECT * FROM categories WHERE id = :id');
			$stmt->execute(array('id' => $id));
			return new Category($stmt->fetch());
		}
		
		static function save($category) {
			
		}
	}
