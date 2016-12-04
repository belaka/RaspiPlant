<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller
{
    public function indexAction(Request $request)
    {    
        $keys = array();
        
        $sensors = $this->getSensorManager()->findAll();
        
        foreach ($sensors as $sensor) {
            $class = $sensor->getClass();
            $id = $sensor->getId();
            $fields = array_map(function($v) use ($id) { return $id . '_' . $v;}, $class::getFields());
            $keys = array_merge($fields, $keys);
        }
        
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $date->modify('-3 hours');
        
        $analytics = $data = array();
        
        foreach ($keys as $key) {
            $keyDatas = $this->getAnalyticsManager()->findByKeyAndDate($key, $date);
            foreach ($keyDatas as $keyData) {
                $keyValue = round($keyData->getEventValue(), 2);
                if ($keyValue > 0 && $keyValue < 1500) {
                    $eventDate = $keyData->getEventDate()->setTimezone(new \DateTimeZone('Europe/Paris'));
                    $analytics[$key][] = [addslashes($eventDate->format('H:i')), round($keyValue, 2)];
                }
            }
            $data[$key] = array(
                'value' => end($analytics[$key])[1],
                'date' => end($analytics[$key])[0]
            );
        }
        
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
    
    protected function getSensorManager()
    {
        return $this->container->get("board.manager.sensor_manager");
    }
    
}
