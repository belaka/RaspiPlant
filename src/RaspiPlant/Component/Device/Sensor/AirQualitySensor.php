<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

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
     * @var ProtocolInterface
     */
    protected $protocol;

    /**
     *
     * @var int
     */
    protected $pin;


    /**
     *
     * @param I2CProtocol $protocol
     * @param int $pin
     * @param string $name
     * @param boolean $debug
     */
    function __construct(I2CProtocol $protocol, $pin, $name, $debug = false) {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->protocol = $protocol;
        $this->name = $name;
        $this->protocol->pinMode($this->pin, "INPUT");

    }

    public function readSensorData() {

        try {

            return json_encode(
                    array(
                        'error' => null,
                        'air_quality' => $this->protocol->analogRead($this->pin)
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
