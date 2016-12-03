<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DeviceController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('BoardBundle:Device:index.html.twig');
    }
}
