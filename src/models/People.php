<?php 
	class Person
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

	class People
	{
		static function getPeople() {
			global $database;
				
			$stmt = $database->prepare('SELECT * FROM people ORDER BY name');
			$stmt->execute();
			$people = array();
			
			while($row = $stmt->fetch())
				$people[] = new Person($row);
			
			return $people;
		}
		
		static function getPersonById($id) {
			global $database;
				
			$stmt = $database->prepare('SELECT * FROM people WHERE id = :id');
			$stmt->execute(array('id' => $id));
			return new Person($stmt->fetch());				
		}
		
		function save($person) {
			
		}
	}
