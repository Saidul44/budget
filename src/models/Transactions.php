<?php 
	require_once('Categories.php');
	require_once('People.php');

	class Transaction
	{
		private $id;
		private $person_id;
		private $category_id;
		private $timestamp;
		private $description;
		private $amount;

		function __construct($fields = array()) {
			if(array_key_exists('id', $fields)) $this->id = $fields['id'];

			$this->setPersonById($fields['person_id']);
			$this->setCategoryById($fields['category_id']);
			$this->setTimestamp($fields['timestamp']);
			$this->setDescription($fields['description']);
			$this->setAmount($fields['amount']);
		}

		function getId() {
			return $this->id;
		}
		
		function getTimestamp() {
			return $this->timestamp;
		}
		
		function setTimestamp($timestamp) {
			$this->timestamp = $timestamp;
		}
				
		function setPersonById($id) {
			if($id == null) {
				$this->person_id = null;
				return;
			}
				
			if($person = People::getPersonById($id))
				$this->person_id = $person->getId();
			else
				throw new Exception('Person_id is note valid.');
		}
		
		function setPerson($person) {
			if($person->getId())
				$this->person_id = $person->getId();
			else
				throw new Exception('Person has not been saved.');
		}
		
		function setCategoryById($id) {
			if($id == null) {
				$this->category_id = null;
				return;
			}
			
			if($category = Categories::getCategoryById($id))
				$this->category_id = $category->getId();
			else
				throw new Exception('Category_id is note valid.');
		}
		
		function setCategory($catgory) {
			if($category->getId())
				$this->category_id = $category->getId();
			else
				throw new Exception('Category has not been saved.');
		}
		
		function getPerson() {
			return People::getPersonById($this->person_id);
		}
		
		function getCategory() {
			return Categories::getCategoryById($this->category_id);
		}
		
		function setDescription($description) {
			$this->description = $description;
		}

		function getDescription() {
			return $this->description;
		}
		
		function setAmount($amount) {
			$this->amount = $amount;
		}
		
		function getAmount() {
			return $this->amount;
		}
	}

	class Transactions
	{
		static function getTransactions() {			
			global $database;
				
			/* Get transactions */
			$stmt = $database->prepare('SELECT t.*, p.name as person_name, c.name as category_name FROM transactions as t, people as p, categories as c where t.person_id = p.id and t.category_id = c.id ORDER BY timestamp, description');
			$stmt->execute();
				
			$transactions = array();
			while($row = $stmt->fetch())
				$transactions[] = new Transaction($row);
			
			return $transactions;			
		}
		
		static function getTransactionById($id) {
			global $database;
			
			$stmt = $database->prepare('SELECT * FROM transactions WHERE id = :id');
			$stmt->execute(array('id' => $id));
			return new Transaction($stmt->fetch());
		}
		
		static function save($transaction) {
			global $database;
			
			if($transaction->getId()) {
				// Update
				$stmt = $database->prepare('UPDATE transactions SET person_id = :person_id, category_id = :category_id, timestamp = :timestamp, description = :description , amount = :amount WHERE id = :id');
				$stmt->execute(array(
						'id' => $transaction->getId(),
						'person_id' => $transaction->getPerson()->getId(),
						'category_id' => $transaction->getCategory()->getId(),
						'timestamp' => $transaction->getTimestamp(),
						'description' => $transaction->getDescription(),
						'amount' => $transaction->getAmount()					
				));
			} else {
				// Create
				$stmt = $database->prepare('INSERT INTO transactions(person_id, category_id, timestamp, description, amount) ' .
						'VALUES(:person_id, :category_id, :timestamp, :description, :amount)');

				$stmt->execute(array(
						'person_id' => $transaction->getPerson()->getId(),
						'category_id' => $transaction->getCategory()->getId(),
						'timestamp' => $transaction->getTimestamp(),
						'description' => $transaction->getDescription(),
						'amount' => $transaction->getAmount()
				));
			}
		}
	}
