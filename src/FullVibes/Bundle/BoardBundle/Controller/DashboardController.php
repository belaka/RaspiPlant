<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FullVibes\Bundle\BoardBundle\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use FullVibes\Component\Form\Type\AtomizerFormType;
use FullVibes\Component\Actuator;
use FullVibes\Component\Actuator\AbstractActuator;
use FullVibes\Component\Device\I2CDevice;
use FullVibes\Bundle\BoardBundle\Event\EventSubscriber;

class DashboardController extends Controller
{
    public function indexAction()
    {
        $keys = array('temperature', 'humidity', 'moisture_1', 'moisture_2', 'air_quality');
        
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone('Europe/Paris'));
        $date->modify('-3 hours');
        
        $analytics = $data = array();
        
        foreach ($keys as $key) {
            $keyDatas = $this->getAnalyticsManager()->findByKeyAndDate($key, $date);
            foreach ($keyDatas as $keyData) {
                $keyValue = round($keyData->getEventValue(), 2);
                if ($keyValue > 0 && $keyValue < 1000) {
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
    
    /**
     * @param Request $request
     * @return Response
     */
    public function atomizerAction(Request $request)
    {
        $form = $this->createForm(AtomizerFormType::class, null, array('method' => 'POST'));
        $form->handleRequest($request);

        if ($form->isValid()) {
            
            $form_values = $request->request->all();
            
            $value = (int) $form_values['atomizer_form']['state'];
            
            $atomizerPin = 2;
            $fd = wiringpii2csetup(AbstractActuator::RPI_I2C_ADDRESS);
            $device = new I2CDevice($fd);
            $atomizer = new Actuator\WaterAtomizationActuator($device, $atomizerPin);
            $atomizer->writeStatus($value);
            //$this->dispatchEvents($atomizer, $form_values['state']);
        }

        return $this->render(
            'BoardBundle:Board:atomizer.html.twig', 
            array(
                'form' => $form->createView(),
            )
        );

    }
    
    protected function getAnalyticsManager()
    {
        return $this->container->get("board.manager.analytics_manager");
    }
        
    /**
     * @param Actuator $device
     * @param int $value
     */
    protected function dispatchEvents($device, $value)
    {
        $dispatcher = new EventDispatcher();
        $dispatcher->addSubscriber(new EventSubscriber());
        $event = new Event\AtomizerActuatorEvent($device, $value);
        $dispatcher->dispatch(Event\AtomizerActuatorEvent::NAME, $event);
    }
    
}
