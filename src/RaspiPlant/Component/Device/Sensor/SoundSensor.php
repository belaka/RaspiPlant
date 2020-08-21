<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

/**
 *
 */
class SoundSensor extends AbstractSensor
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
     * SoundSensor constructor.
     * @param I2CProtocol $protocol
     * @param $pin
     * @param $name
     * @param bool $debug
     */
    function __construct(I2CProtocol $protocol, $pin, $name, $debug = false)
    {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->protocol = $protocol;

        $this->protocol->pinMode($this->pin, "OUTPUT");
    }

    /**
     * @return array
     */
    public static function getFields()
    {

        return array(
            'sound' => array('min' => 0, 'max' => 100, 'unit' => '%')
        );
    }

    public function readSensorData()
    {

        try {

            return json_encode(
                array(
                    'error' => null,
                    'sound' => $this->protocol->analogRead($this->pin)
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

}
