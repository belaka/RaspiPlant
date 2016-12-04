<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

/**
 * 
 */
class SoundSensor extends AbstractSensor {

    const RPI_I2C_ADDRESS = 0x04; // I2C Address of Raspberry
    
    const I2C_UNUSED_VALUE = 0;
    
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
    
    function __construct($pin, $debug = false) {
        
        $this->debug = $debug;
        $this->pin = $pin;
        
        $this->fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $this->device = new I2CDevice($this->fd);
        
        $this->device->pinMode($this->pin, "OUTPUT");
    }

    public function readSensorData() {
        		
        try {
            
            return json_encode(
                    array(
                        'error' => null,
                        'sound' => $this->device->analogRead($this->pin)
                    )
            ); 
            
        } catch (\Exception $exc) {
            
            return json_encode(
                    array(
                        'error' => $exc->getMessage(),
                        'sound' => 0
                    )
            );
        }
    }
    
    public static function getFields() {
        		
        return array(
            'sound'
        );
    }
}
