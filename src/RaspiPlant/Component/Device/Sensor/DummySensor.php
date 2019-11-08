<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;
use RaspiPlant\Component\Device\AddressableInterface;

/**
 * Class DummySensor
 * @package RaspiPlant\Component\Device\Sensor
 */
class DummySensor extends AbstractSensor implements AddressableInterface
{
    const I2C_ADDRESS = '0x00';

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
     * DummySensor constructor.
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
    }

    /**
     * @return array
     */
    public static function getFields()
    {
        return array(
            'dummy_value' => array('min' => 0, 'max' => 100, 'unit' => 'DUM')
        );
    }

    public function readSensorData()
    {

        try {

            return json_encode(
                array(
                    'error' => null,
                    'dummy_value' => rand(0, 100)
                )
            );

        } catch (\Exception $exc) {

            return json_encode(
                array(
                    'error' => $exc->getMessage(),
                    'dummy_value' => 0
                )
            );
        }
    }

    /**
     * @return int|string
     */
    public static function getAddress()
    {
        return self::I2C_ADDRESS;
    }

}
