<?php
	require_once('Controller.php');
	
	class DefaultController extends Controller
	{
		function indexAction() 
		{
			global $database;
			
			$this->setFragmentValue('title', 'Dashboard');
			//$this->setFragment('main-area', 'views/categories.php');

			$query = "SELECT c.name AS category, SUM(amount) AS total, MONTH(timestamp) as period FROM transactions AS t, categories AS c, subcategories AS s WHERE s.category_id = c.id AND t.subcategory_id = s.id GROUP BY period, c.id ORDER BY period DESC, category";
			$stmt = $database->prepare($query);
			$stmt->execute();
			
			$data = array();
			
			while($row = $stmt->fetch()) {
				$data[] = $row;
			}
			
			$this->setFragment('main-area', 'by_period_category.php',
					array('transactions' => $data));
				
			
			return $this->renderPage('base.php');
		}

		function phoneAction()
		{
			global $database;

			$start_date = strtotime('2014-06-23');
			$table = array(500 => 10, 750 => 15, 1000 => 20, 1500 => 30);

			$user = 'ivar';

			$query = 'SELECT * FROM transactions WHERE subcategory_id = 5 AND description LIKE "%' . $user . '%"';
			$stmt = $database->prepare($query);
			$stmt->execute();

			$days = 0;

			while($row = $stmt->fetch()) {
				$days += $table[$row['amount']];
			}

			echo('Validity expires on: ' . date('Y-m-d', $start_date + $days * 3600 * 24));
		}		
	}
