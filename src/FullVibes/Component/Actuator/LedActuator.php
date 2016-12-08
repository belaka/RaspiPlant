<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

class LedActuator extends AbstractActuator {
    
    /**
     *
     * @var I2CDevice
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
     * @var boolean
     */
    protected $debug;
    
    /**
     * 
     * @param int $pin
     * @param boolean $debug
     */
    public function __construct(I2CDevice $device, $pin, $name, $debug = false) {
        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->device = $device;
        $this->device->pinMode($this->pin, "OUTPUT");
    }
    
    /**
     * 
     * @return int
     */
    public function readStatus() {
        # read relay status
        $status = $this->device->digitalRead(self::DIGITAL_READ_COMMAND, $this->pin);
        
        return $status;
    }
    
    /**
     * 
     * @param int $status
     * @return int
     */
    public function writeStatus($status) {
        # switch relay on
        $this->device->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, $status, 0);
        
        return $this->readStatus();
    }

    public static function getControls() {
        return array(
            'state' => array(
                'on' => 1,
                'off' => 0
            )
        );
    }
}
