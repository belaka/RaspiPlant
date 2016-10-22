<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

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
    
    function __construct($pin, $type = self::DHT_SENSOR_WHITE, $debug = false) {
        
        $this->debug = $debug;
        $this->type = $type;
        $this->pin = $pin;
        
        $this->fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $this->device = new I2CDevice($this->fd);
        
        $this->device->pinMode($this->pin, "INPUT");
        
    }

    public function readSensorData() {
        		
        try {
            
            $value = $this->device->analogRead($this->pin);
            sleep(0.2);
            
            return $value;
            
        } catch (\Exception $exc) {
            
            echo $exc->getMessage();
        }
    }
}
