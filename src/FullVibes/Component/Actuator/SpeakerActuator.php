<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

class SpeakerActuator extends AbstractActuator {
    
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
     * SpeakerActuator constructor.
     * @param I2CDevice $device
     * @param $pin
     * @param $name
     * @param bool $debug
     */
    public function __construct(I2CDevice $device, $pin, $name, $debug = false) {
        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->device = $device;
        $this->device->pinMode($this->pin, "OUTPUT");
    }

    /**
     * @throws \Exception
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
        # switch speaker on
        $this->device->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, $status, 0);
        
        return 1;
    }
    
    /**
     * 
     */
    public function __destruct() {
        $this->device->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, 0, 0);
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
