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
	}
	