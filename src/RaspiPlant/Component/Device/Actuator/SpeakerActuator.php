<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class SpeakerActuator extends AbstractActuator
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
     * SpeakerActuator constructor.
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
        $this->protocol->pinMode($this->pin, "OUTPUT");
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
     * @throws \Exception
     */
    public function readStatus()
    {
        throw new \Exception('NOT IMPLEMENTED');
    }

    /**
     *
     * @param int $status
     * @return int
     */
    public function writeStatus($status)
    {
        # switch speaker on
        $this->protocol->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, $status, 0);

        return 1;
    }

    /**
     *
     */
    public function __destruct()
    {
        $this->protocol->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, 0, 0);
    }
}
