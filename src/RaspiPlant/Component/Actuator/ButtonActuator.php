<?php

namespace RaspiPlant\Component\Actuator;

use RaspiPlant\Component\Device\I2CDevice;

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
     * ButtonActuator constructor.
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
     * @param null $status
     * @throws \Exception
     */
    public function writeStatus($status = NULL) {
        throw new \Exception('NOT IMPLEMENTED');
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
