<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

class WaterAtomizationActuator extends AbstractActuator {
    
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
        throw new \Exception('NOT IMPLEMENTED');
    }
    
    /**
     * 
     * @param int $status
     * @return int
     */
    public function writeStatus($status) {

        # switch water atomization on relay on
        $this->device->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, $status, 0);
        
        return 1;
    }
    
    /**
     * 
     */
    public function __destruct() {
        $this->device->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, 0, 0);
    }
}
