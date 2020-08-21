<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class SwitchActuator extends AbstractActuator
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
     * SwitchActuator constructor.
     * @param I2CProtocol $device
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

    /**
     * @return array
     */
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
        $status = $this->protocol->digitalRead(self::DIGITAL_READ_COMMAND, $this->pin);
        sleep(0.1);
        return $status;
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
