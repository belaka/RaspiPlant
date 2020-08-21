<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class ButtonActuator extends AbstractActuator
{

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
        $this->protocol->pinMode($this->pin, "INPUT");
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
        return $this->protocol->digitalRead(I2CProtocol::DIGITAL_READ_CMD, $this->pin);
    }

    /**
     * @param null $status
     * @throws \Exception
     */
    public function writeStatus($status = NULL)
    {
        throw new \Exception('NOT IMPLEMENTED');
    }


}
