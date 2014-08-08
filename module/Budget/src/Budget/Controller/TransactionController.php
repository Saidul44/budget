<?php
	namespace Budget\Controller;

	use Budget\Model\Transaction;	
	
	use Zend\View\Model\ViewModel;
	use Zend\Mvc\Controller\AbstractActionController;
		

	function parseDecimal($str) {
		$str = str_replace(",", ".", $str);
		$parts = explode('.', $str);
		
		$value = 100 * intval($parts[0]);
		
		if(count($parts) > 1)
			$value += intval($parts[1]);

		return $value;
	}


	class TransactionController extends AbstractActionController
	{
		protected $categoryTable;
		protected $subcategoryTable;
		protected $transactionTable;
		

		protected function getCategoryTable()
		{
			if(!$this->categoryTable) {
				$sm = $this->getServiceLocator();
				$this->categoryTable = $sm->get('Budget\Model\CategoryTable');
			}
				
			return $this->categoryTable;
		}

		
		protected function getSubcategoryTable()
		{
			if(!$this->subcategoryTable) {
				$sm = $this->getServiceLocator();
				$this->subcategoryTable = $sm->get('Budget\Model\SubcategoryTable');
			}
				
			return $this->subcategoryTable;
		}

		
		protected function getTransactionTable()
		{
			if(!$this->transactionTable) {
				$sm = $this->getServiceLocator();
				$this->transactionTable = $sm->get('Budget\Model\TransactionTable');
			}
		
			return $this->transactionTable;
		}		
		
		
		function postToTransactions()
		{
			$i = 0;
			$transactions = array();
			
			while(array_key_exists('amount_' . $i, $_POST)) {
				$amount = parseDecimal($_POST['amount_' . $i]);
				
				if($amount == 0) {
					$i++;
					continue;
				}
				
				$transaction = new Transaction();
				$transaction->timestamp = $_POST['timestamp_' . $i];
				$transaction->subcategory_id = $_POST['subcategory_id_' . $i];
				$transaction->description = $_POST['description_' . $i];
				$transaction->amount = parseDecimal($_POST['amount_' . $i]);

				$transactions[] = $transaction; 
				
				$i++;
			}
			
			return $transactions;
		}
		
		
		function listAction()		
		{
			global $database;
			
			/* Get year/week to show */						
			$routeMatch = $this->getEvent()->getRouteMatch();
			
			$year = $routeMatch->getParam('year');
			$week = $routeMatch->getParam('week');
			
			/* Compute start and end state */
			$dto = new \DateTime();
			$dto->setISODate($year, $week);
						
			$timespan[0] = $dto->getTimestamp();
			$timespan[1] = $dto->modify('+6 days')->getTimestamp();		
			
			
			/* Get data for time period */
			$query = "SELECT category.name AS category, subcategory.name AS subcategory, transaction.*					
					  FROM transaction, category, subcategory 					
					  WHERE subcategory.category_id = category.id AND subcategory_id = subcategory.id AND timestamp >= :timespan_0 AND timestamp <= :timespan_1 
					  ORDER BY timestamp, transaction.id";

			$timespan_0 = date('Y-m-d', $timespan[0]);
			$timespan_1 = date('Y-m-d', $timespan[1]);
			
			$stmt = $database->prepare($query);
			$stmt->bindParam('timespan_0', $timespan_0);
			$stmt->bindParam('timespan_1', $timespan_1);
			$stmt->execute();
				
			/* Get data for weekly overview */
			$query = "SELECT
					WEEK(timestamp) + 1 as week,
					SUM(amount) as total,
					category.name AS category
					FROM transaction, category, subcategory
					WHERE subcategory.category_id = category.id AND subcategory_id = subcategory.id 
					GROUP BY week, category_id
					ORDER BY week DESC, category";

			$first_week = $week - 10;
			
			$stmt_overview = $database->prepare($query);
			$stmt_overview->bindParam('first_week', $first_week);
			$stmt_overview->bindParam('last_week', $week);
			$stmt_overview->execute();
			
			/* Output stage */					
			$viewModel = new ViewModel(array(
				'year' => $year,
				'week' => $week,
				'timespan'     => $timespan,
				'transactions' => $stmt,
				'overview'     => $stmt_overview));

			return $viewModel;
		}
		

		function addAction()
		{
			$subcategories = $this->getSubcategoryTable()->fetchAll();
			
			$subcategories_array = array();
			foreach($subcategories as $subcategory) {
				$subcategory->category = 
					$this->getCategoryTable()->getCategory($subcategory->category_id); 
				$subcategories_array[$subcategory->id] = $subcategory;
			}
			
			
			$transactions = array();
			$errors = array();
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {				
				$transactions = $this->postToTransactions();
				// Check for errors

				if(count($errors) == 0) {					
					foreach($transactions as $transaction)
						$this->getTransactionTable()->saveTransaction($transaction);

					return $this->redirect()->toRoute('transaction', array('action' => 'list'));
				}
			}
		
			/* Output stage */
			$viewModel = new ViewModel(array(
					'subcategories' => $subcategories_array,
					'transactions'  => $transactions
			));
			
			return $viewModel;			
		}		
		
		
		function editAction()
		{
		}
		
		
		function deleteAction()
		{
			$routeMatch = $this->getEvent()->getRouteMatch();				
			$id = $routeMatch->getParam('id');
				
			$transaction = $this->getTransactionTable()->getTransaction($id);

			$week = date('W', strtotime($transaction->timestamp));
			$year = date('o', strtotime($transaction->timestamp));
			
			$this->getTransactionTable()->deleteTransaction($id);

			return $this->redirect()->toRoute('transaction_list', array('week' => $week, 'year' => $year));
		}
	}
