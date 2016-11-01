<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

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

    /**
     * 
     * @param int $pin
     * @param boolean $debug
     */

    public function __construct($debug = false) {

        $this->debug = $debug;

        $this->fd = wiringpii2csetup(self::I2C_MOTOR_DRIVER_ADD);
        $this->device = new I2CDevice($this->fd);
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
        throw new \Exception('NOT IMPLEMENTED');
    }

    public function motorDirectionSet($direction) {
        wiringPiI2CWriteBuffer (self::I2C_MOTOR_DRIVER_ADD, self::DIRECTION_SET, $direction, 0, 0, 0, 4);
        //bus . write_i2c_block_data(self::I2C_MOTOR_DRIVER_ADD, self::DIRECTION_SET, [$direction, 0]);
        sleep(.02);
    }

    public function motorSpeedSetAB($motorSpeedA, $motorSpeedB) {
        //$speedA = $this->mapVals($motorSpeedA, 0, 100, 0, 255);
        //$speedB = $this->mapVals($motorSpeedB, 0, 100, 0, 255);
        wiringPiI2CWriteBuffer (self::I2C_MOTOR_DRIVER_ADD, self::MOTOR_SPEED_SET, 0, $motorSpeedA, $motorSpeedB, 0, 4);
        //bus . write_i2c_block_data(self::I2C_MOTOR_DRIVER_ADD, self::MOTOR_SPEED_SET, []);
        sleep(.02);
    }

    //Maps speed from 0-100 to 0-255
    public function mapVals($value, $leftMin, $leftMax, $rightMin, $rightMax) {
        #http://stackoverflow.com/questions/1969240/mapping-a-range-of-values-to-another
        # Figure out how 'wide' each range is
        $leftSpan = $leftMax - $leftMin;
        $rightSpan = $rightMax - $rightMin;

        # Convert the left range into a 0-1 range (float)
        $valueScaled = float($value - $leftMin) / float($leftSpan);

        # Convert the 0-1 range into a value in the right range.
        return intval($rightMin + ($valueScaled * $rightSpan));
    }

    /**
     * 
     */
    public function __destruct() {
        wiringPiI2CWriteBuffer ($this->fd, self::MOTOR_SPEED_SET, 0, 0, 0, 0, 4);
    }

}
