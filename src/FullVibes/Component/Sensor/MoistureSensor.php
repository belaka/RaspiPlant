<?php

namespace FullVibes\Component\Sensor;

/**
 * 
 */
class MoistureSensor extends AbstractSensor {

    /**
     *
     * @var boolean
     */
    protected $debug;
    
    /**
     *
     * @var Device
     */
    protected $device;
    
    /**
     *
     * @var type 
     */
    protected $fd;
    
    /**
     *
     * @var int
     */
    protected $pin;
    
    function __construct($device, $pin, $debug = false) {
        
        $this->debug = $debug;
        $this->pin = $pin;
        $this->device = $device;
    }

    public function readSensorData() {
        		
        try {
            
            return $this->device->analogRead($this->pin);
                        
        } catch (\Exception $exc) {
            
            echo $exc->getMessage();
        }
    }
}
