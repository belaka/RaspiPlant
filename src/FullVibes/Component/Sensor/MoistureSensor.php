<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

/**
 * 
 */
class MoistureSensor extends AbstractSensor {

    const RPI_I2C_ADDRESS = 0x04; // I2C Address of Raspberry
    
    const I2C_UNUSED_VALUE = 0;
    
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
    
    function __construct($pin, $debug = false) {
        
        $this->debug = $debug;
        $this->pin = $pin;
        
        $this->fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $this->device = new I2CDevice($this->fd);
        
        //$this->device->pinMode($this->pin, "OUTPUT");
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
