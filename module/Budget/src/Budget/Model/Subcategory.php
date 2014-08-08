<?php
	namespace Budget\Model;

	class Subcategory
	{
		public $id;
		public $category_id;
		public $name;

		public function exchangeArray($data)
		{
			$this->id 			= (!empty($data['id'])) ? $data['id'] : null;
			$this->category_id  = (!empty($data['category_id'])) ? $data['category_id'] : null;
			$this->name         = (!empty($data['name'])) ? $data['name'] : null;
		}
	}

	/*
	class Subcategories
	{
		static function getSubcategories() {
			global $database;
				
			/* Get categories 
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
	*/
