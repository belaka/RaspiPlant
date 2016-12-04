<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeviceController extends Controller
{
    public function indexAction()
    {
        $devices = $this->getDeviceManager()->findAll();
        
        return $this->render('BoardBundle:Device:index.html.twig', array('devices' => $devices));
    }
    
    protected function getDeviceManager()
    {
        return $this->container->get("board.manager.device_manager");
    }
}
