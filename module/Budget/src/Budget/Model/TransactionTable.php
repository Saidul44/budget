<?php 
	namespace Budget\Model;
	
	use Zend\Db\Sql\Sql;
	use Zend\Db\Sql\Select;
	use Zend\Db\Sql\Where;
	use Zend\Db\TableGateway\TableGateway;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\Sql\Expression;
	
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
		
		public function getOverviewBy($period, $category, $type = 'expenses')
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
					
			$select = new Select();
			$select->from(array('t' => 'transaction'))
				   ->columns(array(
				   		'yearweek' => new Expression('YEARWEEK(timestamp, 3)'),
				   		'yearmonth' => new Expression("DATE_FORMAT(timestamp, '%Y%m')"),
				   		'year' => new Expression('YEAR(timestamp)'),
				   		'week' => new Expression('WEEK(timestamp, 3)'),
				   		'month' => new Expression('MONTH(timestamp)'),
				   		'total' => new Expression('SUM(amount)')))
				   ->join(array('s' => 'subcategory'), 's.id = t.subcategory_id')
				   ->join(array('c' => 'category'), 'c.id = s.category_id', array('category' => 'name'))
				   ->group($group_by)
				   ->order(array('t.timestamp DESC', 'category'));

			if($type == 'income') {
				$select->where('amount < 0');
			} elseif($type == 'expenses') {
				$select->where('amount > 0');
			} else {
				throw Exception('Invalid value for type argument.');
			}

			// Execute statement
			$statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
			$result = $statement->execute();

			// Fetch rows and group by month
			$records = array();
			
			foreach($result as $row) {
				if($period == 'week')
					$records[$row['yearweek']][] = $row;
				elseif($period == 'month')
					$records[$row['yearmonth']][] = $row;
				elseif($period == 'year')
					$records[$row['year']][] = $row;
			}
			
			return $records;
		}
	}
	