<?php

namespace FullVibes\Component\Actuator;

use FullVibes\Component\Device\I2CDevice;

class PotentiometerActuator extends AbstractActuator {

    // Reference voltage of ADC is 5v
    const ADC_REF = 5;
    // Vcc of the grove interface is normally 5v
    const GROVE_VCC = 5;
    // Full value of the rotary angle is 300 degrees, as per it's specs (0 to 300)
    const FULL_ANGLE = 300;

    /**
     * 
     * @param int $pin
     * @param boolean $debug
     */
    public function __construct($pin, $debug = false) {

        $this->debug = $debug;

        $this->pin = $pin;

        $this->fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $this->device = new I2CDevice($this->fd);

        $this->device->pinMode($this->pin, "INPUT");
    }

    /**
     * 
     * @return int
     */
    public function readStatus() {

        // Read sensor value from potentiometer
        $value = $this->device->analogRead($this->pin);

        // Calculate voltage
        $voltage = round((float) ($value) * self::ADC_REF / 1023, 2);

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
     * 
     * @param int $status
     * @return int
     */
    public function writeStatus($status = NULL) {
        throw new \Exception('NOT IMPLEMENTED');
    }

}
