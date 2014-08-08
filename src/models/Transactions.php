<?php 
	require_once('Categories.php');
	require_once('Subcategories.php');
	
	class Transaction
	{
		private $id;
		private $subcategory_id;
		private $timestamp;
		private $description;
		private $amount;

		function __construct($fields = array()) {
			if(array_key_exists('id', $fields)) $this->id = $fields['id'];

			$this->setSubcategoryById($fields['subcategory_id']);
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
				throw new Exception('Person_id is not valid.');
		}
				
		function setSubcategoryById($id) {
			if($id == null) {
				$this->subcategory_id = null;
				return;
			}
			
			if($subcategory = Subcategories::getSubcategoryById($id))
				$this->subcategory_id = $subcategory->getId();
			else
				throw new Exception('Subcategory_id is not valid.');
		}
		
		function setSubcategory($catgory) {
			if($subcategory->getId())
				$this->subcategory_id = $subcategory->getId();
			else
				throw new Exception('Subcategory has not been saved.');
		}
				
		function getSubcategory() {
			return Subcategories::getSubcategoryById($this->subcategory_id);
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
		static function getTransactionCount()
		{
			global $database;
			
			$stmt = $database->prepare('SELECT COUNT(*) as count FROM transactions');
			$stmt->execute();
			
			$row = $stmt->fetch();
			return $row['count'];
		}
		
		static function getTransactions($start, $count) {			
			global $database;
				
			/* Get transactions */
			$stmt = $database->prepare('SELECT t.*, c.name AS category_name, s.name AS subcategory_name ' .
					'FROM transactions AS t, categories AS c, subcategories AS s ' .
					'WHERE t.subcategory_id = s.id AND c.id = s.category_id ' .
					'ORDER BY timestamp, description ' .
					'LIMIT :start, :count');
			$stmt->bindValue('start', $start, PDO::PARAM_INT);
			$stmt->bindValue('count', $count, PDO::PARAM_INT);
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
				$stmt = $database->prepare('UPDATE transactions SET subcategory_id = :subcategory_id, timestamp = :timestamp, description = :description , amount = :amount WHERE id = :id');
				$stmt->execute(array(
						'id' => $transaction->getId(),
						'subcategory_id' => $transaction->getSubcategory()->getId(),
						'timestamp' => $transaction->getTimestamp(),
						'description' => $transaction->getDescription(),
						'amount' => $transaction->getAmount()					
				));
			} else {
				// Create
				$stmt = $database->prepare('INSERT INTO transactions(subcategory_id, timestamp, description, amount) ' .
						'VALUES(:subcategory_id, :timestamp, :description, :amount)');
				$stmt->execute(array(
						'subcategory_id' => $transaction->getSubcategory()->getId(),
						'timestamp' => $transaction->getTimestamp(),
						'description' => $transaction->getDescription(),
						'amount' => $transaction->getAmount()
				));
			}
		}
		
		static function delete($transaction) {
			global $database;
			
			$stmt = $database->prepare('DELETE FROM transactions WHERE id = :id');
			$stmt->execute(array('id' => $transaction->getId())); 
		}
	}
