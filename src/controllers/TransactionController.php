<?php
	require_once('Controller.php');
	require_once('models/People.php');
	require_once('models/Categories.php');
	require_once('models/Transactions.php');
	
	class TransactionController extends Controller
	{						
		function addAction()
		{
			print_r($_GET);
			print_r($_POST);
		}
		
		function postToTransactions()
		{
			$n = count($_POST['category_id']);
			$transactions = array();
			
			for($i = 0; $i < $n; $i++) {
				$transactions[$i] = new Transaction(array(
					'timestamp' => $_POST['timestamp'][$i],
					'person_id' => $_POST['person_id'][$i],
					'category_id' => $_POST['category_id'][$i],
					'description' => $_POST['description'][$i],
					'amount' => $_POST['value'][$i]
				));
			}
			
			return $transactions;
		}
		
		function listAction() {
			if(array_key_exists('action', $_POST))
				$action = $_POST['action'];
			else
				$action = 'list';

			// Validate form
			if($action == 'add') {
				$transactions = $this->postToTransactions();

				// Add data to database
				foreach($transactions as $transaction)
					Transactions::save($transaction);				
			}
			
			$transactions = Transactions::getTransactions();
			$people = People::getPeople();			
			$categories = Categories::getCategories();
			
			$this->setFragmentValue('title', 'Transactions');
			$this->setFragment('main-area', 'transactions.php', 
					array('transactions' => $transactions, 
						  'people' => $people,
						  'categories' => $categories));
			
			return $this->renderPage('base.php');
		}
	}
