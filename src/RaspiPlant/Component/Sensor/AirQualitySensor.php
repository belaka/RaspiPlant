<?php

namespace RaspiPlant\Component\Sensor;

use RaspiPlant\Component\Device\I2CDevice;

/**
 *
 */
class AirQualitySensor extends AbstractSensor
{

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
        $this->device = $device;
        $this->name = $name;
        $this->device->pinMode($this->pin, "INPUT");

    }

    public function readSensorData() {

        try {

            return json_encode(
                    array(
                        'error' => null,
                        'air_quality' => $this->device->analogRead($this->pin)
                    )
            );

        } catch (\Exception $exc) {

            return json_encode(
                    array(
                        'error' => $exc->getMessage(),
                        'air_quality' => 0
                    )
            );
        }
    }

    /**
     * @return array
     */
    public static function getFields() {

        return array(
            'air_quality' => array('min' => 0, 'max' => 1000, 'unit' => '')
        );
    }

}
