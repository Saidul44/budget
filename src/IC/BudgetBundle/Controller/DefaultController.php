<?php

namespace IC\BudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render(
            'ICBudgetBundle:Default:index.html.twig', array('name' => $name));
    }
}
