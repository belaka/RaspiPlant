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
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}
