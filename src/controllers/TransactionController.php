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
			
			while(array_key_exists('amount_' . $i, $_POST)) {
				$amount = parseDecimal($_POST['amount_' . $i]);
				
				if($amount == 0) {
					$i++;
					continue;
				}
				
				$transactions[] = new Transaction(array(
					'timestamp' => $_POST['timestamp_' . $i],
					'subcategory_id' => $_POST['subcategory_id_' . $i],
					'description' => $_POST['description_' . $i],
					'amount' => parseDecimal($_POST['amount_' . $i]),
				));
				
				$i++;
			}
			
			return $transactions;
		}
		
		function list2Action() 
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
		
		
		function listAction()		
		{
			global $database;
			
			/* Find timespan to show data for */
			
			if(array_key_exists('date', $_GET))			
				$date = strtotime($_GET['date']);
			else 
				$date = strtotime(date('Y-m-d'));
			
			$dow = date('w', $date);
			$week_number = date('W', $date);
			
			$timespan[0] = $date - ($dow - 1) * 3600 * 24;
			$timespan[1] = $timespan[0] + 6 * 3600 * 24;		
			
			
			/* Get data for time period */
			$query = "SELECT categories.name AS category, subcategories.name AS subcategory, transactions.*					
					  FROM transactions, categories, subcategories 					
					  WHERE subcategories.category_id = categories.id AND subcategory_id = subcategories.id AND timestamp >= :timespan_0 AND timestamp <= :timespan_1 
					  ORDER BY timestamp, transactions.id";

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
					categories.name AS category
					FROM transactions, categories, subcategories
					WHERE subcategories.category_id = categories.id AND subcategory_id = subcategories.id 
					GROUP BY week, category_id
					ORDER BY week DESC, category";

			$first_week = $week_number - 10;
			$last_week = $week_number;
			$stmt_overview = $database->prepare($query);
			$stmt_overview->bindParam('first_week', $first_week);
			$stmt_overview->bindParam('last_week', $first_week);
			$stmt_overview->execute();
			
			/* Output stage */
			$options = array();
			$options['timespan'] = $timespan;
			$options['transactions'] = $stmt;
			$options['overview'] = $stmt_overview;
			
			$this->setFragment('main-area', 'transaction_list2.php', $options);
			return $this->renderPage('base.php');
		}
		

		function addAction()
		{
			$subcategories = Subcategories::getSubcategories();
			
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
					array('transactions' => $transactions, 'subcategories' => $subcategories));
				
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
