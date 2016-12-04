<?php

namespace FullVibes\Component\Sensor;

/**
 * 
 */
class LightSensor extends AbstractSensor {

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
            
            return json_encode(
                    array(
                        'error' => null,
                        'light' => $this->device->analogRead($this->pin)
                    )
            ); 
            
        } catch (\Exception $exc) {
            
            return json_encode(
                    array(
                        'error' => $exc->getMessage(),
                        'light' => 0
                    )
            );
        }
    }
    
    public static function getFields() {
        		
        return array(
            'light'
        );
    }
    
    
}
