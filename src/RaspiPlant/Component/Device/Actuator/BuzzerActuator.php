<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class BuzzerActuator extends AbstractActuator {

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
     * BuzzerActuator constructor.
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
        throw new \Exception('NOT IMPLEMENTED');
    }

    /**
     *
     * @param int $status
     * @return int
     */
    public function writeStatus($status) {
        # switch relay on
        $this->protocol->digitalWrite(I2CProtocol::DIGITAL_WRITE_CMD, $this->pin, $status, 0);

        return 1;
    }

    /**
     *
     */
    public function __destruct() {
        $this->protocol->digitalWrite(I2CProtocol::DIGITAL_WRITE_CMD, $this->pin, 0, 0);
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
