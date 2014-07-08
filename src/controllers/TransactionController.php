<?php
	require_once('Controller.php');
	require_once('models/People.php');
	require_once('models/Categories.php');
	require_once('models/Transactions.php');
	
	function parseDecimal($str) {
		$str = str_replace(",", ".", $str);
		$parts = explode('.', $str);
		
		$value = 100 * intval($parts[0]);
		
		if(count($parts) > 1)
			$value += intval($parts[1]);

		return $value;
	}
	
	class TransactionController extends Controller
	{
		function postToTransactions()
		{
			$i = 0;
			$transactions = array();
			
			while(array_key_exists('person_id_' . $i, $_POST)) {
				$amount = parseDecimal($_POST['amount_' . $i]);
				
				if($amount == 0) {
					$i++;
					continue;
				}
				
				$transactions[] = new Transaction(array(
					'timestamp' => $_POST['timestamp_' . $i],
					'person_id' => $_POST['person_id_' . $i],
					'category_id' => $_POST['category_id_' . $i],
					'description' => $_POST['description_' . $i],
					'amount' => parseDecimal($_POST['amount_' . $i]),
				));
				
				$i++;
			}
			
			return $transactions;
		}
		
		function listAction() 
		{
			$transactions_per_page = 10;
			$n_transactions = Transactions::getTransactionCount();
			$n_pages = ceil($n_transactions / $transactions_per_page);

			if(array_key_exists('page', $_GET)) {
				$page = intval($_GET['page']);
			} else {
				$page = $n_pages;
			}
						
			$start = ($page - 1) * $transactions_per_page;
			$nav = array('page' => $page, 'pages' => $n_pages);
			
			$transactions = Transactions::getTransactions($start, $transactions_per_page);
			$people = People::getPeople();			
			$categories = Categories::getCategories();
			
			$this->setFragmentValue('title', 'Transactions');
			$this->setFragment('main-area', 'transaction_list.php', 
					array('transactions' => $transactions, 
						  'people' => $people,
						  'categories' => $categories,
						  'navigation' => $nav));
			
			return $this->renderPage('base.php');
		}

		function addAction()
		{
			$people = People::getPeople();
			$categories = Categories::getCategories();
			
			$transactions = array();
			$errors = array();
			
			if($_SERVER['REQUEST_METHOD'] == 'POST') {				
				$transactions = $this->postToTransactions();
				// Check for errors
				
				if(count($errors) == 0) {
					foreach($transactions as $transaction) {
						Transactions::save($transaction);
					}
											
					return $this->redirect('transaction', 'list');
				}
			}
			
			$this->setFragmentValue('title', 'Add transactions');
			$this->setFragment('main-area', 'transaction_form.php',
					array('transactions' => $transactions, 'people' => $people, 'categories' => $categories));
				
			return $this->renderPage('base.php');
		}		
		
		function editAction()
		{
			$id = intval($_GET['id']);
			
			$transaction = Transactions::getTransactionById($id);
			
			$this->setFragmentValue('title', 'Edit transaction #' . $id);
			$this->setFragment('main-area', 'transaction_form.php',
					array('transactions' => array($transaction)));
				
			return $this->renderPage('base.php');
		}
		
		function deleteAction()
		{
			$id = intval($_POST['transaction_id']);
			$transaction = Transactions::getTransactionById($id);
				
			Transactions::delete($transaction);
				
			return $this->redirect('transaction', 'list');
				
		}
	}
