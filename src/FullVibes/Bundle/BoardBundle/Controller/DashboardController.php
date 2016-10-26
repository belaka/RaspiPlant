<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $keys = array('temperature','humidity','light','air_quality', 'moisture');
        $lastEvents = $this->getAnalyticsManager()->getRepository()->findBy(array('eventKey' => $keys), array('eventDate' => 'DESC'), count($keys));
        $data = [];
        
        foreach ($lastEvents as $analytic)  {
            $data[$analytic->getEventKey()] = round($analytic->getEventValue(),2);
            $date = $analytic->getEventDate();
        }
        
        $date = new \DateTime;
        $date->modify('-30 minutes');
        $temperatures = $this->getAnalyticsManager()->findByKeyAndDate('temperature', $date);
        $tempArray = [];
        
        foreach ($temperatures as $temperature) {
            $tempValue = round($temperature->getEventValue(), 2);
            if ($tempValue > 0 && $tempValue < 90) {
                $tempArray[] = [addslashes($temperature->getEventDate()->format('H:i')), round($tempValue, 2)];
            }
        }
        
        $humidities = $this->getAnalyticsManager()->findByKeyAndDate('humidity', $date);
        $humArray = [];
        
        foreach ($humidities as $humidity) {
            $humValue = round($humidity->getEventValue(), 2);
            if ($humValue > 0 && $humValue < 90) {
                $humArray[] = [addslashes($humidity->getEventDate()->format('H:i')), round($humValue, 2)];
            }
        }
               
        return $this->render(
                'BoardBundle:Dashboard:index.html.twig',
                array(
                    'data' => $data, 
                    'date' => $date, 
                    'temperatures' => json_encode($tempArray, JSON_UNESCAPED_SLASHES), 
                    'humidities' => json_encode($humArray, JSON_UNESCAPED_SLASHES)
                )
        );
    }
    
    protected function getAnalyticsManager()
    {
        return $this->container->get("board.manager.analytics_manager");
    }
}
