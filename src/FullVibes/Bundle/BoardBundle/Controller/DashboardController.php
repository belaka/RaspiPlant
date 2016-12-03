<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction(Request $request)
    {
        $keys = array('temperature', 'humidity', 'moisture_1', 'moisture_2', 'air_quality', 'i2c_temperature', 'i2c_humidity', 'i2c_pressure', 'i2c_dewPoint');
        
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $date->modify('-3 hours');
        
        $analytics = $data = array();
        
        foreach ($keys as $key) {
            $keyDatas = $this->getAnalyticsManager()->findByKeyAndDate($key, $date);
            foreach ($keyDatas as $keyData) {
                $keyValue = round($keyData->getEventValue(), 2);
                if ($keyValue > 0 && $keyValue < 1500) {
                    $analytics[$key][] = [addslashes($keyData->getEventDate()->format('H:i')), round($keyValue, 2)];
                }
            }
            $data[$key] = array(
                'value' => end($analytics[$key])[1],
                'date' => end($analytics[$key])[0]
            );
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
