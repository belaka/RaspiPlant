<?php

namespace RaspiPlant\Bundle\BoardBundle\Controller;

use RaspiPlant\Bundle\BoardBundle\Manager\AnalyticsManager;
use RaspiPlant\Bundle\BoardBundle\Manager\SensorManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    /**
     * @param SensorManager $sensorManager
     * @param AnalyticsManager $analyticsManager
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function indexAction(SensorManager $sensorManager, AnalyticsManager $analyticsManager)
    {
        $keys = array();

        $sensors = $sensorManager->findAllActive();

        foreach ($sensors as $sensor) {
            $class = $sensor->getClass();
            $id = $sensor->getId();
            $name = $sensor->getSlug();
            $fields = array_map(function($k) use ($id, $name) { return array('name' => $name, 'key' => $id . '_' . $k);}, array_keys($class::getFields()));
            $keys = array_merge($fields, $keys);
        }

        $keynames = array_map(function ($v) {return $v['name'].'_'.$v['key'];}, $keys);

        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $date->modify('-3 hours');

        $analytics = $data = array();

        foreach ($keys as $sensorData) {

            $keyDatas = $analyticsManager->findByKeyAndDate($sensorData['key'], $date);

            foreach ($keyDatas as $keyData) {
                $keyValue = round($keyData->getEventValue(), 2);
                if ($keyValue > 0 && $keyValue < 1500) {
                    $eventDate = $keyData->getEventDate()->setTimezone(new \DateTimeZone('Europe/Paris'));
                    $analytics[$sensorData['name'] .'_'. $sensorData['key']][] = [addslashes($eventDate->format('H:i')), round($keyValue, 2)];
                }
            }
            $data[$sensorData['name'] .'_'. $sensorData['key']] = array(
                'value' => end($analytics[$sensorData['name'] .'_'. $sensorData['key']])[1],
                'date' => end($analytics[$sensorData['name'] .'_'. $sensorData['key']])[0]
            );
        }

        return $this->render(
                'dashboard/index.html.twig',
                array(
                    'keys' => $keynames,
                    'data' => $data,
                    'date' => $date,
                    'analytics' => json_encode($analytics, JSON_UNESCAPED_SLASHES)
                )
        );
    }

}
