<?php 
	namespace Budget\Model;
	
	use Zend\Db\TableGateway\TableGateway;
	
	class TransactionTable
	{
		protected $tableGateway;

		public function __construct(TableGateway $tableGateway)
		{
			$this->tableGateway = $tableGateway;
		}
		
		public function fetchAll()
		{
			$resultSet = $this->tableGateway->select();
			return $resultSet;
		}
		
		public function getTransaction($id)
		{
			$rowset = $this->tableGateway->select(array('id' => $id));
			$row = $rowset->current();
			
			if(!$row)
				throw new \Exception("Could not find transaction");
			
			return $row;
		}

		public function saveTransaction(Transaction $transaction)
		{
			$data = array(
				'subcategory_id' => $transaction->subcategory_id,
				'timestamp' => $transaction->timestamp,
				'description' => $transaction->description,
				'amount' => $transaction->amount			
			);
			
			$id = $transaction->id;
			
			if($id == 0) {
				$this->tableGateway->insert($data);
			} else {
					if($this->getTransaction($id))
						$this->tableGateway->update($data, array('id' => $id));
			}
		}
		
		public function deleteTransaction($id)
		{
			$this->tableGateway->delete(array('id' => $id));
		}
		
		
		public function getTransactionsInPeriod($from, $to)
		{ 
			global $database;

			$query = "SELECT category.name AS category, subcategory.name AS subcategory, transaction.*
					  FROM transaction, category, subcategory
					  WHERE subcategory.category_id = category.id AND subcategory_id = subcategory.id AND timestamp >= :timespan_0 AND timestamp <= :timespan_1
					  ORDER BY timestamp, transaction.id";
		
			$from = date('Y-m-d', $from);
			$to   = date('Y-m-d', $to);
			
			$stmt = $database->prepare($query);
			$stmt->bindParam('timespan_0', $from);
			$stmt->bindParam('timespan_1', $to);
			$stmt->execute();
			
			return $stmt;
		}
		
		public function getOverviewBy($period, $category)
		{
			$group_by = [];
			
			if($period == 'week')
				$group_by[] = 'yearweek';
			elseif($period == 'month')
				$group_by[] = 'yearmonth';
			elseif($period == 'year')
				$group_by[] = 'year';
			
			if($category)
				$group_by[] = 'category_id';
			
			global $database;
			$query = "SELECT
						YEARWEEK(timestamp, 3) as yearweek,
						WEEK(timestamp, 3) as week,
						MONTH(timestamp) as month,
						SUM(amount) as total,
						category.name AS category
						FROM transaction, category, subcategory
						WHERE subcategory.category_id = category.id AND subcategory_id = subcategory.id
						GROUP BY " . join(", ", $group_by) . "
						ORDER BY timestamp DESC, category";
		
			$stmt = $database->prepare($query);
			$stmt->execute();
			
			return $stmt;
		}
		
	}
	