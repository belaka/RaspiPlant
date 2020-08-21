<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class MotorDriverActuator extends AbstractActuator {

    const MOTOR_SPEED_SET = 0x82;
    const PWM_FREQUENCE_SET = 0x84;
    const DIRECTION_SET = 0xaa;
    const MOTOR_SET_A = 0xa1;
    const MOTOR_SET_B = 0xa5;
    const NOTHING = 0x01;
    const ENABLE_STEPPER = 0x1a;
    const DISABLE_STEPPER = 0x1b;
    const STEPPER_NU = 0x1c;
    const I2C_MOTOR_DRIVER_ADD = 0x0f;  //Set the address of the I2CMotorDriver
    //const I2C_MOTOR_DRIVER_ADD = 0x0a;

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
     * MotorDriverActuator constructor.
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
    }

    /**
     * @throws \Exception
     */
    public function readStatus() {
        throw new \Exception('NOT IMPLEMENTED');
    }

    /**
     * @param $status
     * @throws \Exception
     */
    public function writeStatus($status) {
        if ($status == 0)  {
            $this->controlMotor('A', 0);
            usleep(100000);
            $this->controlMotor('B', 0);
        } elseif($status == 1) {
            $this->controlMotor('A', 255);
            usleep(100000);
            $this->controlMotor('B', 255);
        }
    }


    public function motorDirectionSet($direction) {

        //bus . write_i2c_block_data(self::I2C_MOTOR_DRIVER_ADD, self::DIRECTION_SET, [$direction, 0]);
        $this->protocol->writeBuffer(self::DIRECTION_SET, $direction, 0, 0, 0, 1);


    }

    public function controlMotor($motor , $speed, $direction = 0b1010) {

        if (!in_array($motor, array('A', 'B'))) {
            throw new \Exception('NOT A RECOGNIZED MOTOR');
        }

        $motor_set = self::MOTOR_SET_A;

        if ($motor === 'B') {
            $motor_set = self::MOTOR_SET_B;
        }

        $this->protocol->writeBuffer($motor_set, $direction, $speed, self::NOTHING, self::NOTHING, 2);
    }

    public function controlMotorA($speed, $direction = 0b1010) {
        $this->controlMotor('A', $speed, $direction);
    }

    public function controlMotorB($speed, $direction = 0b1010) {
        $this->controlMotor('B', $speed, $direction);
    }

    //Maps speed from 0-100 to 0-255
    public function mapVals($value, $leftMin, $leftMax, $rightMin, $rightMax) {
        #http://stackoverflow.com/questions/1969240/mapping-a-range-of-values-to-another
        # Figure out how 'wide' each range is
        $leftSpan = $leftMax - $leftMin;
        $rightSpan = $rightMax - $rightMin;

        # Convert the left range into a 0-1 range (float)
        $valueScaled = floatval($value - $leftMin) / floatval($leftSpan);

        # Convert the 0-1 range into a value in the right range.
        return intval($rightMin + ($valueScaled * $rightSpan));
    }

    /**
     *
     */
    public function __destruct() {
        $this->controlMotor('A', 0);
        $this->controlMotor('B', 0);
    }

    public static function getControls() {
        return array(
            'state' => array(
                'on' => 1,
                'off' => 0
            ),
            'speed' => array(
                'min' => 0,
                'max' => 255
            ),
            'direction' => array(
                'forth' => 0b1010,
                'back' => 0b0101,
            )
        );
    }

}
