<?php

namespace IC\BudgetBundle\Controller;

use IC\BudgetBundle\Entity\Transaction;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    public function indexAction()
    {
        $transactions = $this->getDoctrine()->
            getRepository('ICBudgetBundle:Transaction')->
            findAll();
        
        $transactions = array(
            array('date' => 12392931, 'description' => 'Description', 'value' => '10.00'),
            array('date' => 12392931, 'description' => 'Description', 'value' => '10.00')
            );
        
        return $this->render(
            'ICBudgetBundle:Transaction:list.html.twig',
            array('transactions' => $transactions));
    }
    
    public function newAction()
    {
        $transaction = new Transaction();
        
        $form = $this->createFormBuilder($transaction)
            ->add('subcategory_id', 'text')
            ->add('date', 'date')
            ->add('description', 'text')
            ->getForm();

        return $this->render('ICBudgetBundle:Transaction:new.html.twig', array(
            'form' => $form->createView()));
    }
}