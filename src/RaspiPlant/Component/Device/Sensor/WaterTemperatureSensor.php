<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\W1Protocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

/**
 *
 */
class WaterTemperatureSensor extends AbstractSensor {

    /**
     *
     * @var boolean
     */
    protected $debug;

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
     * WaterTemperatureSensor constructor.
     * @param W1Protocol $protocol
     * @param $pin
     * @param $name
     * @param bool $debug
     */
    function __construct(W1Protocol $protocol, $pin, $name, $debug = false) {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->protocol = $protocol;
    }

    public function readSensorData() {

        try {

            $deviceId = '28-0000073b00e1';
            $tString = $this->protocol->readAddress($deviceId);

            return json_encode(
                array(
                    'error' => null,
                    'water_temperature' => $this->extractTemperature($tString)
                )
            );

        } catch (\Exception $exc) {

            return json_encode(
                array(
                    'error' => $exc->getMessage(),
                    'water_temperature' => 0
                )
            );
        }
    }

    /**
     * @param $str
     * @return mixed
     */
    private function extractTemperature($str)
    {
        // We want the value after the t= on the 2nd line
        preg_match("/t=(.+)/", preg_split("/\n/", $str)[1], $matches);
        $celsius = round($matches[1] / 1000, 2); //round the results

        return $celsius;
    }

    /**
     * @return array
     */
    public static function getFields() {
        return  array(
            'water_temperature' => array('min' => -50, 'max' => 125, 'unit' => 'C°'),
        );
    }

}
