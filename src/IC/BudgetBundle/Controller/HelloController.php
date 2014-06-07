<?php

namespace IC\BudgetBundle\Controller;

use IC\BudgetBundle\Entity\Category;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class HelloController extends Controller
{
    public function indexAction($name)
    {
        $category = $this->getDoctrine()->
            getRepository('ICBudgetBundle:Category')->
            findByName(array('name' => 'Transportation'));
        
        if(!$category) {
            $category = new Category();
            $category->setName('Transportation');
        
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();
        }
        
        //$em->remove($product);
        //$em->flush();
    
        // $id = $category->getId();
    
        //$session = $request->getSession();
        //$foobar = $session->get('foobar');
    
        $product = null;
        if (!$product) {
            //throw $this->createNotFoundException('The product does not exist');
        }    
    
        return $this->render(
            'ICBudgetBundle:Hello:index.html.twig',
            array('name' => $name, 'category' => $category[0]->getName())
        );
    }
}