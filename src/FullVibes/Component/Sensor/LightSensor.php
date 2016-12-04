<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

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
    
    /**
     *
     * @var string
     */
    protected $name;
    
    /**
     * 
     * @param I2CDevice $device
     * @param int $pin
     * @param string $name
     * @param boolean $debug
     */
    function __construct(I2CDevice $device, $pin, $name, $debug = false) {
        
        $this->debug = $debug;
        $this->pin = $pin;
        $this->device = $device;
        $this->name = $name;
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
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}
