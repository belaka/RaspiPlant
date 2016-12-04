<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

/**
 * Abstract Class for communicating with an actuator.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
abstract class AbstractActuator implements ActuatorInterface {
    
    const RPI_I2C_ADDRESS = 0x04; // I2C Address of GrovePi
    
    const DIGITAL_WRITE_COMMAND = 2; // Digital Write Command byte
    
    const DIGITAL_READ_COMMAND = 1; // Digital Write Command byte
    
    const ACTUATOR_WAIT_TIME = 1;
    
    /**
     *
     * @var I2CDevice
     */
    protected $device;
    
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
    public function __construct($device, $pin, $debug = false) {

        $this->debug = $debug;

        $this->pin = $pin;
        
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

}
