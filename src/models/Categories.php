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

		function setParentById($id) {
			if($id == null) {
				$this->parent_id = null;
				return;
			}
				
			if($category = Categories::getCategoryById($id))
				$this->parent_id = $category->getId();
			else
				throw new Exception('Parent_id is not valid.');
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
		
		static function getRootCategory() {
			global $database;
			
			$stmt = $database->prepare("SELECT * FROM categories WHERE parent_id IS NULL");
			$stmt->execute();
			$row = $stmt->fetch();
			
			if($row)
				return new Category($row);
			else
				return null;
		}
		
		static function getCategoryById($id) {
			global $database;
			
			$stmt = $database->prepare('SELECT * FROM categories WHERE id = :id');
			$stmt->execute(array('id' => $id));
			$row = $stmt->fetch();
			
			if($row)			
				return new Category($row);
			else
				return null;
		}
		
		static function save($category) {
			global $database;

			if($category->getParent() == null) {
				throw new Exception('Invalid parent id.');
			}
			
			if($category->getId()) {
				// Update
				$stmt = $database->prepare('UPDATE categories SET parent_id = :parent_id, name = :name WHERE id = :id');
				$stmt->execute(array(
						'id' => $category->getId(),
						'parent_id' => $category->getParent()->getId(),
						'name' => $category->getName()
				));
			} else {				
				// Create
				$stmt = $database->prepare('INSERT INTO categories(name, parent_id) ' .
						'VALUES(:name, :parent_id)');

				$stmt->execute(array(
						'name' => $category->getName(),
						'parent_id' => $category->getParent()->getId()
				));
			}
		}
		
		static function delete($category) {
			global $database;
			$stmt = $database->prepare('DELETE FROM categories WHERE id = :id');
			$stmt->execute(array('id' => $category->getId()));
		}
	}
