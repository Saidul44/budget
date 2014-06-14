<?php

namespace IC\BudgetBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'ICBudgetBundle:Default:index.html.twig', array());
    }
}
