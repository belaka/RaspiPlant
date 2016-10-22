<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

class SwitchActuator extends AbstractActuator {
    
    /**
     * 
     * @param int $pin
     * @param boolean $debug
     */
    public function __construct($pin, $debug = false) {

        $this->debug = $debug;

        $this->pin = $pin;
        
        $this->fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $this->device = new I2CDevice($this->fd);
        
        $this->device->pinMode($this->pin, "INPUT");
    }
    
    /**
     * 
     * @return int
     */
    public function readStatus() {
        # read relay status
        $status = $this->device->digitalRead(self::DIGITAL_READ_COMMAND, $this->pin);
        sleep(0.1);
        return $status;
    }
    
    
    /**
     * 
     * @param int $status
     * @return int
     */
    public function writeStatus($status = NULL) {
        throw new \Exception('NOT IMPLEMENTED');
    }
}
