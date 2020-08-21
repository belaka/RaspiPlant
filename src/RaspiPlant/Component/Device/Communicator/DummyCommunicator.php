<?php

namespace RaspiPlant\Component\Device\Communicator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class DummyCommunicator extends AbstractCommunicator
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
     * @var string
     */
    protected $msg = "Hello World!";

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

    /**
     *
     * @return int
     */
    public function read()
    {
        # read relay status
        return $this->msg;
    }

    /**
     * @param null $msg
     */
    public function write($msg = NULL)
    {
        $this->msg = $msg;
    }


}
