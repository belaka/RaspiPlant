<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $keys = array('temperature_1','humidity_1','moisture_1','air_quality', 'temperature_2', 'humidity_2', 'moisture_2');
        $lastEvents = $this->getAnalyticsManager()->getRepository()->findBy(array('eventKey' => $keys), array('eventDate' => 'DESC'), count($keys));
        $data = [];
        
        foreach ($lastEvents as $analytic)  {
            $data[$analytic->getEventKey()] = array(
                'value' => round($analytic->getEventValue(),2),
                'date' => $analytic->getEventDate()
            );
     
        }
        
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $date->modify('-3 hours');
        
        
        $analytics = array();
        
        foreach ($keys as $key) {
            $keyDatas = $this->getAnalyticsManager()->findByKeyAndDate($key, $date);
            foreach ($keyDatas as $keyData) {
                $keyValue = round($keyData->getEventValue(), 2);
                if ($keyValue > 0 && $keyValue < 1000) {
                    $analytics[$key][] = [addslashes($keyData->getEventDate()->format('H:i')), round($keyValue, 2)];
                }
            }
        }
        
        //die(dump($analytics));
        
        return $this->render(
                'BoardBundle:Dashboard:index.html.twig',
                array(
                    'keys' => $keys,
                    'data' => $data, 
                    'date' => $date,
                    'analytics' => json_encode($analytics, JSON_UNESCAPED_SLASHES)
                )
        );
    }
    
    protected function getAnalyticsManager()
    {
        return $this->container->get("board.manager.analytics_manager");
    }
}
