<?php

namespace FullVibes\Component\Sensor;

/**
 * 
 */
class AirQualitySensor extends AbstractSensor {

    
    
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
        $this->device->pinMode($this->pin, "INPUT");
        
    }

    public function readSensorData() {
        		
        try {
            
            return $this->device->analogRead($this->pin);
            
        } catch (\Exception $exc) {
            
            echo $exc->getMessage();
        }
    }
}
