<?php

namespace FullVibes\Component\Sensor;

/**
 * 
 */
class AirQualitySensor extends AbstractSensor 
{

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
                        'air_quality' => $this->device->analogRead($this->pin)
                    )
            ); 
            
        } catch (\Exception $exc) {
            
            return json_encode(
                    array(
                        'error' => $exc->getMessage(),
                        'air_quality' => 0
                    )
            );
        }
    }
    
    public function getFields() {
        		
        return array(
            'air_quality'
        );
    }
}
