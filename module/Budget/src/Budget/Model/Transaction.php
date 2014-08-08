<?php
 	namespace Budget\Model;

	class Transaction
	{
		public $id;
		public $subcategory_id;
		public $timestamp;
		public $description;
		public $amount;

		public function exchangeArray($data) {
			$thid->id             = (!empty($data['id'])) ? $data['id'] : null;
			$this->subcategory_id = (!empty($data['subcategory_id'])) ? $data['subcategory_id'] : null;
			$this->timestamp      = (!empty($data['timestamp'])) ? $data['timestamp'] : null;
			$this->description    = (!empty($data['description'])) ? $data['description'] : null;
			$this->amount         = (!empty($data['amount'])) ? $data['amount'] : null;
		}
		
	}

	/*
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
				
			/* Get transactions 
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
				/ Update
				$stmt = $database->prepare('UPDATE transactions SET subcategory_id = :subcategory_id, timestamp = :timestamp, description = :description , amount = :amount WHERE id = :id');
				$stmt->execute(array(
						'id' => $transaction->getId(),
						'subcategory_id' => $transaction->getSubcategory()->getId(),
						'timestamp' => $transaction->getTimestamp(),
						'description' => $transaction->getDescription(),
						'amount' => $transaction->getAmount()					
				));
			} else {
				/ Create
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
*/