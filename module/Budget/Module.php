<?php

namespace Budget;

use Budget\Model\Category;
use Budget\Model\Subcategory;
use Budget\Model\Transaction;

use Budget\Model\CategoryTable;
use Budget\Model\SubcategoryTable;
use Budget\Model\TransactionTable;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
	public function onBootstrap(MvcEvent $e) 
	{
		$eventManager = $e->getApplication()->getEventManager();
		$moduleRouteListener = new ModuleRouteListener();
		$moduleRouteListener->attach($eventManager);
	}

	
	public function getConfig() 
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array( 
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
				)));			
	}
	
	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'Budget\Model\CategoryTable' => function($sm) {
					$tableGateway = $sm->get('CategoryTableGateway');
					$table = new CategoryTable($tableGateway);
					return $table;
				},

				'CategoryTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Category());
					return new TableGateway('category', $dbAdapter, null, $resultSetPrototype);
				},

				'Budget\Model\SubcategoryTable' => function($sm) {
					$tableGateway = $sm->get('SubcategoryTableGateway');
					$table = new SubcategoryTable($tableGateway);
					return $table;
				},
				
				'SubcategoryTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Subcategory());
					return new TableGateway('subcategory', $dbAdapter, null, $resultSetPrototype);
				},

				'Budget\Model\TransactionTable' => function($sm) {
					$tableGateway = $sm->get('TransactionTableGateway');
					$table = new TransactionTable($tableGateway);
					return $table;
				},
				
				'TransactionTableGateway' => function($sm) {
					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
					$resultSetPrototype = new ResultSet();
					$resultSetPrototype->setArrayObjectPrototype(new Transaction());
					return new TableGateway('transaction', $dbAdapter, null, $resultSetPrototype);
				}				
				
			),
		);
	}
}
