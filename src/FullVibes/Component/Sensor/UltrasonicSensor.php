<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

/**
 * 
 */
class UltrasonicSensor extends AbstractSensor {

    const RPI_I2C_ADDRESS = 0x04; // I2C Address of Raspberry
    
    const I2C_UNUSED_VALUE = 0;
    
    const ULTRASONIC_READ_CMD = 7;
    
    /**
     *
     * @var boolean
     */
    protected $debug;
    
    /**
     *
     * @var int
     */
    protected $type;
    
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
        
    }

    public function readSensorData() {
        		
        try {
            
            $this->device->digitalWrite(self::ULTRASONIC_READ_CMD, $this->pin, self::I2C_UNUSED_VALUE, self::I2C_UNUSED_VALUE);
            sleep(0.2);
            
            $number = wiringPiI2CReadBuffer ($this->fd, 7, 0, 0, 32);
            $result = array_map ( function($val){return hexdec($val);} , explode(':', $number));
            
            return ($result[2] * 256 + $result[3]);
            
        } catch (\Exception $exc) {
            
            echo $exc->getMessage();
        }
    }
}
