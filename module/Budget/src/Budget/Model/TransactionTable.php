<?php 
	namespace Budget\Model;
	
	use Zend\Db\Sql\Sql;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Where;
	use Zend\Db\TableGateway\TableGateway;
	use Zend\Db\ResultSet\ResultSet;
	
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

			$select = new Select();			
			$select->from(array('t' => 'transaction'))
				   ->columns(array('id', 'timestamp', 'description', 'amount'))
				   ->join(array('s' => 'subcategory'), 's.id = t.subcategory_id', array('subcategory' => 'name'))
				   ->join(array('c' => 'category'), 'c.id = s.category_id', array('category' => 'name'))
				   ->where(array('t.timestamp >= ?' => date('Y-m-d', $from), 't.timestamp <= ?' => date('Y-m-d', $to)))
				   ->order(array('t.timestamp', 't.id'));
			
			$statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
			$result = $statement->execute();
			
			return $result;
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
	