<?php

namespace FullVibes\Bundle\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use FullVibes\Bundle\BoardBundle\Event;
use Symfony\Component\EventDispatcher\EventDispatcher;
use FullVibes\Component\Form\Type\AtomizerFormType;
use FullVibes\Component\Actuator;
use FullVibes\Component\Actuator\AbstractActuator;
use FullVibes\Component\Device\I2CDevice;
use FullVibes\Bundle\BoardBundle\Event\EventSubscriber;
use FullVibes\Component\WiringPi\WiringPi;

class ActuatorController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('BoardBundle:Actuator:index.html.twig');
    }
    
    /**
     * @param Request $request
     * @return Response
     */
    public function atomizerAction(Request $request)
    {
        $form = $this->createForm(AtomizerFormType::class, null, array('method' => 'POST'));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $form_values = $request->request->all();
            
            $value = (int) $form_values['atomizer_form']['state'];
           	
            $atomizerPin = 5;
            $fd = WiringPi::wiringPiI2CSetup(AbstractActuator::RPI_I2C_ADDRESS);
            $grovepi = new I2CDevice($fd);
            $atomizer = new Actuator\WaterAtomizationActuator($grovepi, $atomizerPin);
            usleep(60000);
	    $atomizer->writeStatus($value);

            
            $this->addFlash(
                'notice',
                'Value was set to ' . (int) $value
            );
            
            //$this->dispatchEvents($atomizer, $form_values['state']);
        }

        return $this->render(
            'BoardBundle:Actuator:atomizer.html.twig', 
            array(
                'form' => $form->createView(),
            )
        );

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