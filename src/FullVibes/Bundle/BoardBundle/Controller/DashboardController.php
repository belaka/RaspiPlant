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
            $name = $sensor->getName();
            $fields = array_map(function($v) use ($id, $name) { return array('name' => $name, 'key' => $id . '_' . $v);}, $class::getFields());
            $keys = array_merge($fields, $keys);
        }
        
        $keynames = array_map(function ($v) {return $v['name'].':'.$v['key'];}, $keys);
        
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $date->modify('-3 hours');
        
        $analytics = $data = array();
        
        foreach ($keys as $sensorData) {
            //dump($sensorData['key']);
            $keyDatas = $this->getAnalyticsManager()->findByKeyAndDate($sensorData['key'], $date);
            //die(dump($keyDatas));
            foreach ($keyDatas as $keyData) {
                $keyValue = round($keyData->getEventValue(), 2);
                if ($keyValue > 0 && $keyValue < 1500) {
                    $eventDate = $keyData->getEventDate()->setTimezone(new \DateTimeZone('Europe/Paris'));
                    $analytics[$sensorData['name'] .':'. $sensorData['key']][] = [addslashes($eventDate->format('H:i')), round($keyValue, 2)];
                }
            }
            $data[$sensorData['name'] .':'. $sensorData['key']] = array(
                'value' => end($analytics[$sensorData['name'] .':'. $sensorData['key']])[1],
                'date' => end($analytics[$sensorData['name'] .':'. $sensorData['key']])[0]
            );
        }
        
        return $this->render(
                'BoardBundle:Dashboard:index.html.twig',
                array(
                    'keys' => $keynames,
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
