<?php

namespace IC\BudgetBundle\Controller;

use IC\BudgetBundle\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    public function indexAction()
    {
        $categories = $this->getDoctrine()->
            getRepository('ICBudgetBundle:Category')->
            findAll();
        
        return $this->render(
            'ICBudgetBundle:Category:list.html.twig',
            array('categories' => $categories));
    }
/*
ByName(array('name' => 'Transportation'));
        if(!$category) {
            $category = new Category();
            $category->setName('Transportation');
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }*/
        
        //$em->remove($product);
        //$em->flush();
    
        // $id = $category->getId();
    
        //$session = $request->getSession();
        //$foobar = $session->get('foobar');
    
    /*    $product = null;
        if (!$product) {
            //throw $this->createNotFoundException('The product does not exist');
        }    
    
        return $this->render(
            'ICBudgetBundle:Hello:index.html.twig',
            array('name' => 'x', 'category' => 'y')
        );
    }*/
}