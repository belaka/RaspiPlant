<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;
use RaspiPlant\Component\Device\AddressableInterface;

/**
 * Class DummyActuator
 * @package RaspiPlant\Component\Device\Actuator
 */
class DummyActuator extends AbstractActuator implements AddressableInterface
{

    const I2C_ADDRESS = '0x00';

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
     * @var boolean
     */
    protected $debug;

    /**
     *
     * @var int
     */
    protected $status = 0;

    /**
     * ButtonActuator constructor.
     * @param I2CProtocol $protocol
     * @param $pin
     * @param $name
     * @param bool $debug
     */
    public function __construct(I2CProtocol $protocol, $pin, $name, $debug = false)
    {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->protocol = $protocol;
    }

    public static function getControls()
    {
        return array(
            'state' => array(
                'on' => 1,
                'off' => 0
            )
        );
    }

    /**
     *
     * @return int
     */
    public function readStatus()
    {
        # read relay status
        return $this->status;
    }

    /**
     * @param null $status
     * @throws \Exception
     */
    public function writeStatus($status = NULL)
    {
        $this->status = $status;
    }

    /**
     * @return int|string
     */
    public static function getAddress()
    {
        return self::I2C_ADDRESS;
    }
}
