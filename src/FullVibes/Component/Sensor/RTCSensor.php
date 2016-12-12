<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

class RTCSensor extends AbstractSensor {

    /**
     *
     * @var I2CDevice
     */
    protected $device;

    /**
     *
     * @var integer
     */
    protected $pin;

    /**
     *
     * @var boolean
     */
    protected $debug;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     * RTCSensor constructor.
     * @param I2CDevice $device
     * @param $pin
     * @param $name
     * @param bool $debug
     * @throws \Exception
     */
    public function __construct(I2CDevice $device, $pin, $name, $debug = false) {

        $this->device = $device;
        $this->pin = $pin;
        $this->name = $name;
        $this->debug = $debug;

    }

    public function readSensorData() {

        try {

            /**
             * # Read from Grove RTC
            def rtc_getTime():
            write_i2c_block(address, rtc_getTime_cmd + [unused, unused, unused])
            time.sleep(.1)
            read_i2c_byte(address)
            number = read_i2c_block(address)
            return number **/

            /*******
             *
             * # RTC get time
             *   rtc_getTime_cmd = [30]
             */

            usleep(100000);

            return json_encode(
                    array(
                        'error' => null,
                        'date' => '',
                    )
            );    
        } catch (\Exception $exc) {
            return json_encode(
                    array(
                        'error' => $exc->getMessage(),
                        'date' => 0
                    )
            ); 
        }
    }

    /**
     * @return array
     */
    public static function getFields() {
        return  array(
            'date' => array('min' => 0, 'max' => 100, 'unit' => '')
        );
    }

}
