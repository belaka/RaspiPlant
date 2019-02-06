<?php

namespace RaspiPlant\Component\Sensor;

use RaspiPlant\Component\Device\I2CDevice;

/**
 *
 */
class SoundSensor extends AbstractSensor {

    /**
     *
     * @var boolean
     */
    protected $debug;

    /**
     *
     * @var Device
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

    /**
     * @return array
     */
    public static function getFields() {

        return array(
            'sound' => array('min' => 0, 'max' => 100, 'unit' => '%')
        );
    }

}
