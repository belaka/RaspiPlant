<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BoardController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('BoardBundle:Board:index.html.twig');
    }
}
