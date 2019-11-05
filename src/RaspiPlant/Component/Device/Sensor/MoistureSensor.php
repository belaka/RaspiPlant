<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

/**
 *
 */
class MoistureSensor extends AbstractSensor
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
     * @var string
     */
    protected $name;

    /**
     * MoistureSensor constructor.
     * @param I2CProtocol $protocol
     * @param $pin
     * @param $name
     * @param bool $debug
     */
    function __construct(I2CProtocol $protocol, $pin, $name, $debug = false)
    {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->protocol = $protocol;
        $this->name = $name;
        $this->protocol->pinMode($this->pin, "INPUT");
    }

    /**
     * @return array
     */
    public static function getFields()
    {

        return array(
            'moisture' => array('min' => 0, 'max' => 1000, 'unit' => 'moist')
        );
    }

    public function readSensorData()
    {

        try {

            return json_encode(
                array(
                    'error' => null,
                    'moisture' => $this->protocol->analogRead($this->pin)
                )
            );

        } catch (\Exception $exc) {

            return json_encode(
                array(
                    'error' => $exc->getMessage(),
                    'moisture' => 0
                )
            );
        }
    }

}
