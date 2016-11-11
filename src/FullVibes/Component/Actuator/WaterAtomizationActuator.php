<?php

namespace FullVibes\Component\Actuator;

class WaterAtomizationActuator extends AbstractActuator {
    
    /**
     * 
     * @param int $pin
     * @param boolean $debug
     */
    public function __construct($device, $pin, $debug = false) {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->device = $device;
        
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
