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
        
        $moistures = $this->getAnalyticsManager()->findByKeyAndDate('moisture', $date);
        $moistArray = [];
        
        foreach ($moistures as $moisture) {
            $moistValue = round($moisture->getEventValue(), 2);
            if ($moistValue > 0 && $moistValue < 90) {
                $moistArray[] = [addslashes($moisture->getEventDate()->format('H:i')), round($moistValue, 2)];
            }
        }
        
        $air_qualities = $this->getAnalyticsManager()->findByKeyAndDate('air_quality', $date);
        $airArray = [];
        
        foreach ($air_qualities as $air_quality) {
            $airValue = round($air_quality->getEventValue(), 2);
            if ($airValue > 0 && $airValue < 500) {
                $airArray[] = [addslashes($air_quality->getEventDate()->format('H:i')), round($airValue, 2)];
            }
        }
               
        return $this->render(
                'BoardBundle:Dashboard:index.html.twig',
                array(
                    'data' => $data, 
                    'date' => $date, 
                    'temperatures' => json_encode($tempArray, JSON_UNESCAPED_SLASHES), 
                    'humidities' => json_encode($humArray, JSON_UNESCAPED_SLASHES),
                    'moistures' => json_encode($moistArray, JSON_UNESCAPED_SLASHES),
                    'air_qualities' => json_encode($airArray, JSON_UNESCAPED_SLASHES),
                )
        );
    }
    
    protected function getAnalyticsManager()
    {
        return $this->container->get("board.manager.analytics_manager");
    }
}
