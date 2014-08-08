<?php 
	class Category
	{
		private $id;
		private $name;

		function __construct($fields = array()) {
			if(array_key_exists('id', $fields)) $this->id = $fields['id'];
			if(array_key_exists('name', $fields)) $this->name = $fields['name'];
		}

		function getId() {
			return $this->id;
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
			$stmt = $database->prepare('SELECT c.* FROM categories as c ORDER BY name');
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
			$row = $stmt->fetch();
			
			if($row)			
				return new Category($row);
			else
				return null;
		}
		
		static function save($category) {
			global $database;
			
			if($category->getId()) {
				// Update
				$stmt = $database->prepare('UPDATE categories SET name = :name WHERE id = :id');
				$stmt->execute(array(
						'id' => $category->getId(),
						'name' => $category->getName()
				));
			} else {				
				// Create
				$stmt = $database->prepare('INSERT INTO categories(name) ' .
						'VALUES(:name)');

				$stmt->execute(array(
						'name' => $category->getName(),
				));
			}
		}
		
		static function delete($category) {
			global $database;
			$stmt = $database->prepare('DELETE FROM categories WHERE id = :id');
			$stmt->execute(array('id' => $category->getId()));
		}
	}
