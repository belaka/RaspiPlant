<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class PotentiometerActuator extends AbstractActuator
{

    // Reference voltage of ADC is 5v
    const ADC_REF = 5;
    // Vcc of the grove interface is normally 5v
    const GROVE_VCC = 5;
    // Full value of the rotary angle is 300 degrees, as per it's specs (0 to 300)
    const FULL_ANGLE = 300;

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
     * PotentiometerActuator constructor.
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

        // Read sensor value from potentiometer
        $value = $this->protocol->analogRead($this->pin);

        // Calculate voltage
        $voltage = round((float)($value) * self::ADC_REF / 1023, 2);

        # Calculate rotation in degrees (0 to 300)
        $degrees = round(($voltage * self::FULL_ANGLE) / self::GROVE_VCC, 2);

        return json_encode(
            array(
                'value' => $value,
                'voltage' => $voltage,
                'degrees' => $degrees
            )
        );
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
