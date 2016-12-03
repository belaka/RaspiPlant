<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SensorController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('BoardBundle:Sensor:index.html.twig');
    }
}
