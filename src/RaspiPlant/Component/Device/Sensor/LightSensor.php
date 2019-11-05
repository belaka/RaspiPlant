<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

/**
 *
 */
class LightSensor extends AbstractSensor
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
     *
     * @param I2CProtocol $protocol
     * @param int $pin
     * @param string $name
     * @param boolean $debug
     */
    function __construct(I2CProtocol $protocol, $pin, $name, $debug = false)
    {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->protocol = $protocol;
        $this->name = $name;
        $this->device->pinMode($this->pin, "INPUT");
    }

    /**
     * @return array
     */
    public static function getFields()
    {

        return array(
            'light' => array('min' => 0, 'max' => 1000, 'unit' => 'lux')
        );
    }

    public function readSensorData()
    {

        try {

            return json_encode(
                array(
                    'error' => null,
                    'light' => $this->protocol->analogRead($this->pin)
                )
            );

        } catch (\Exception $exc) {

            return json_encode(
                array(
                    'error' => $exc->getMessage(),
                    'light' => 0
                )
            );
        }
    }

}
