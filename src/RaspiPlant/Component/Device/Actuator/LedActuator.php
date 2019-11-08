<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class LedActuator extends AbstractActuator {

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
     * @var boolean
     */
    protected $debug;

    /**
     * LedActuator constructor.
     * @param I2CProtocol $protocol
     * @param $pin
     * @param $name
     * @param bool $debug
     */
    public function __construct(I2CProtocol $protocol, $pin, $name, $debug = false) {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->protocol = $protocol;
        $this->protocol->pinMode($this->pin, "OUTPUT");
    }

    /**
     *
     * @return int
     */
    public function readStatus() {
        # read relay status
        $status = $this->protocol->digitalRead(self::DIGITAL_READ_COMMAND, $this->pin);

        return $status;
    }

    /**
     *
     * @param int $status
     * @return int
     */
    public function writeStatus($status) {
        # switch relay on
        $this->protocol->digitalWrite(self::DIGITAL_WRITE_COMMAND, $this->pin, $status, 0);

        return $this->readStatus();
    }

    public static function getControls() {
        return array(
            'state' => array(
                'on' => 1,
                'off' => 0
            )
        );
    }
}
