<?php
	namespace Budget\Model;
	
	use Zend\Db\TableGateway\TableGateway;
	
	class CategoryTable
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
		
		public function getCategory($id)
		{
			$rowset = $this->tableGateway->select(array('id' => $id));
			$row = $rowset->current();
				
			if(!$row)
				throw new \Exception("Could not find category");
				
			return $row;
		}
		
		public function saveCategory(Category $category)
		{
			$data = array(
					'name'        => $category->namet
			);
				
			$id = $transaction->id;
				
			if($id == 0) {
				$this->tableGateway->insert($data);
			} else {
				if($this->getTransaction($id))
					$this->tableGateway->update($data, array('id' => $id));
			}
		}
		
		public function deleteCategory($id)
		{
		}
	}