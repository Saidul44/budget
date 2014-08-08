<?php 
	class Subcategory
	{
		private $id;
		private $name;

		function __construct($fields = array()) {
			if(array_key_exists('id', $fields)) $this->id = $fields['id'];
			if(array_key_exists('category_id', $fields)) $this->category_id = $fields['category_id'];
			if(array_key_exists('name', $fields)) $this->name = $fields['name'];
		}

		function getId() {
			return $this->id;
		}

		function getCategory() {
			return Categories::getCategoryById($this->category_id);
		}
		
		function setCategory($category) {
			$this->category_id = $category->getId();
		}

		function setCategoryById($id) {
			if($id == null) {
				$this->category_id = null;
				return;
			}
				
			if($category = Categories::getCategoryById($id))
				$this->category_id = $category->getId();
			else
				throw new Exception('Category_id is not valid.');
		}
				
		function setName($name) {
			$this->name = $name;
		}

		function getName() {
			return $this->name;
		}
	}

	class Subcategories
	{
		static function getSubcategories() {
			global $database;
				
			/* Get categories */
			$stmt = $database->prepare('SELECT * FROM subcategories ORDER BY name');
			$stmt->execute();
			$subcategories = array();
			while($row = $stmt->fetch())
				$subcategories[] = new Subcategory($row);

			return $subcategories;
		}

		static function getSubcategoryById($id) {
			global $database;
			
			$stmt = $database->prepare('SELECT * FROM subcategories WHERE id = :id');
			$stmt->execute(array('id' => $id));
			$row = $stmt->fetch();
			
			if($row)			
				return new Subcategory($row);
			else
				return null;
		}
		
		static function save($subcategory) {
			global $database;
			
			if($subcategory->getId()) {
				// Update
				$stmt = $database->prepare('UPDATE subcategories SET category_id = :category_id, name = :name WHERE id = :id');
				$stmt->execute(array(
						'id' => $category->getId(),
						'category_id' => $category->getCategory()->getId(),
						'name' => $category->getName()
				));
			} else {				
				// Create
				$stmt = $database->prepare('INSERT INTO subcategories(name, category_id) ' .
						'VALUES(:name, :category_id)');

				$stmt->execute(array(
						'name' => $category->getName(),
						'category_id' => $category->getCategory()->getId()
				));
			}
		}
		
		static function delete($subcategory) {
			global $database;
			$stmt = $database->prepare('DELETE FROM subcategories WHERE id = :id');
			$stmt->execute(array('id' => $subcategory->getId()));
		}
	}
