<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SensorController extends Controller
{
    public function indexAction()
    {
        $sensors = $this->getSensorManager()->findAll();
        
        return $this->render('BoardBundle:Sensor:index.html.twig', array('sensors' => $sensors));
    }
    
    protected function getSensorManager()
    {
        return $this->container->get("board.manager.sensor_manager");
    }
}
