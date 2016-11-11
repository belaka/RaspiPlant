<?php

namespace FullVibes\Component\Sensor;

/**
 * 
 */
class AirQualitySensor extends AbstractSensor {

    const AIR_QUALITY_MIN_VALUE = 0;
    
    const AIR_QUALITY_MAX_VALUE = 1000;
    
    const AIR_QUALITY_KEYS = array('air_quality');
    
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
        $this->device->pinMode($this->pin, "OUTPUT");   
    }

    public function readSensorData() {
        		
        try {
            
            return $this->device->analogRead($this->pin);
            
        } catch (\Exception $exc) {
            
            echo $exc->getMessage();
        }
    }
    
    
    public function getRange()
    {
        return array(
            'air_quality' => array(
                'min' => self::AIR_QUALITY_MIN_VALUE,
                'max' => self::AIR_QUALITY_MAX_VALUE
            )
        );
    }
    
    public function getKeys()
    {
        return self::AIR_QUALITY_KEYS;
    }
}
