<?php

namespace FullVibes\Component\Sensor;

/**
 * 
 */
class MoistureSensor extends AbstractSensor {

    const MOISTURE_MIN_VALUE = 0;
    
    const MOISTURE_MAX_VALUE = 1000;
    
    const MOISTURE_KEYS = array('moisture');
    
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
     * @param type $device
     * @param type $pin
     * @param type $debug
     */
    function __construct($device, $pin, $debug = false) {
        
        $this->debug = $debug;
        $this->pin = $pin;
        $this->device = $device;
    }

    /**
     * 
     * @return int
     */
    public function readSensorData() {
        		
        try {
            
            $this->device->pinMode($this->pin, "INPUT");
            
            usleep(1800);
            
            $value = $this->device->analogRead($this->pin);
            
            return $value;
            
        } catch (\Exception $exc) {
            
            echo $exc->getMessage();
        }
    }
    
    public function getRange()
    {
        return array(
            'moisture' => array(
                'min' => self::MOISTURE_MIN_VALUE,
                'max' => self::MOISTURE_MAX_VALUE
            )
        );
    }
    
    public function getKeys()
    {
        return self::DHT_KEYS;
    }
}
