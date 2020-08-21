<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class RTCSensor extends AbstractSensor
{

    const RTC_I2C_ADDRESS = 0x68;

    /**
     *
     * @var ProtocolInterface
     */
    protected $protocol;

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

    protected $sec = 0;
    protected $min = 0;
    protected $hours = 0;
    protected $day = 0;
    protected $date = 0;
    protected $month = 0;
    protected $year = 0;

    /**
     * RTCSensor constructor.
     * @param I2CProtocol $protocol
     * @param $pin
     * @param $name
     * @param bool $debug
     * @throws \Exception
     */
    public function __construct(I2CProtocol $protocol, $pin, $name, $debug = false)
    {

        $this->protocol = $protocol;
        $this->pin = $pin;
        $this->name = $name;
        $this->debug = $debug;

    }

    /**
     * @return array
     */
    public static function getFields()
    {
        return array(
            'date' => array('min' => 0, 'max' => 100, 'unit' => '')
        );
    }

    public function readSensorData()
    {

        try {

            /**
             * # Read from Grove RTC
             * def rtc_getTime():
             * write_i2c_block(address, rtc_getTime_cmd + [unused, unused, unused])
             * time.sleep(.1)
             * read_i2c_byte(address)
             * number = read_i2c_block(address)
             * return number **/

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

}
