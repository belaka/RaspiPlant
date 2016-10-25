<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $temperature = $this->getAnalyticsManager()->getRepository()->findOneBy(array('eventKey' => 'temperature'), array('eventDate' => 'ASC'));
        $humidity = $this->getAnalyticsManager()->getRepository()->findOneBy(array('eventKey' => 'humidity'), array('eventDate' => 'ASC'));
        $air_quality = $this->getAnalyticsManager()->getRepository()->findOneBy(array('eventKey' => 'air_quality'), array('eventDate' => 'ASC'));
        $moisture = $this->getAnalyticsManager()->getRepository()->findOneBy(array('eventKey' => 'moisture'), array('eventDate' => 'ASC'));
        
        return $this->render(
                'BoardBundle:Dashboard:index.html.twig',
                array('data' => array(
                    'temperature' => $temperature  ? round($temperature->getEventValue(),2) : 0, 
                    'humidity' => $humidity ? round($humidity->getEventValue(),2) : 0, 
                    'air_quality' => $air_quality ? round($air_quality->getEventValue(),2) : 0, 
                    'moisture' => $moisture ? round($moisture->getEventValue(), 2) : 0
                )
            )
        );
    }
    
    protected function getAnalyticsManager()
    {
        return $this->container->get("board.manager.analytics_manager");
    }
}
