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
        $this->name = $name;
        $this->device = $device;
        
    }

    public function readSensorData() {
        		
        try {
            
            $this->device->digitalWrite(self::ULTRASONIC_READ_CMD, $this->pin, self::I2C_UNUSED_VALUE, self::I2C_UNUSED_VALUE);
            sleep(0.2);
            
            $number = wiringPiI2CReadBuffer ($this->fd, self::ULTRASONIC_READ_CMD, 0, 0, 32);
            $result = array_map ( function($val){return hexdec($val);} , explode(':', $number));
            
            return json_encode(
                    array(
                        'error' => null,
                        'distance' => ($result[2] * 256 + $result[3])
                    )
            ); 
            
        } catch (\Exception $exc) {
            
            return json_encode(
                    array(
                        'error' => $exc->getMessage(),
                        'distance' => 0
                    )
            );
        }
    }
    
    public static function getFields() {
        		
        return array(
            'distance'
        );
    }
}
