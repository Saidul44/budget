<?php
	namespace Budget\Model;
	
	use Zend\Db\Sql\Select;
	use Zend\Db\TableGateway\TableGateway;
	
	class SubcategoryTable
	{
		protected $tableGateway;
		
		public function __construct(TableGateway $tableGateway)
		{
			$this->tableGateway = $tableGateway;
		}
		
		public function fetchAll()
		{
			$resultSet = $this->tableGateway->select(function (Select $select) {
				$select->order('name');
			});
			
			return $resultSet;
		}
		
		public function getSubcategory($id)
		{
			$rowset = $this->tableGateway->select(array('id' => $id));
			$row = $rowset->current();
				
			if(!$row)
				throw new \Exception("Could not find subcategory");
				
			return $row;
		}
		
		public function saveSubcategory(Subcategory $subcategory)
		{
			$data = array(
					'category_id' => $category->category_id,
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
		
		public function deleteSubcategory($id)
		{
		}
	}