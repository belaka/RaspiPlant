<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

class ButtonActuator extends AbstractActuator {
    
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
        $this->device->pinMode($this->pin, "INPUT");
    }
    
    /**
     * 
     * @return int
     */
    public function readStatus() {
        # read relay status
        return $this->device->digitalRead(self::DIGITAL_READ_COMMAND, $this->pin);
    }
    
    
    /**
     * 
     * @param int $status
     * @return int
     */
    public function writeStatus($status = NULL) {
        throw new \Exception('NOT IMPLEMENTED');
    }
    
    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
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
