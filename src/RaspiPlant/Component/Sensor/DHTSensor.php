<?php

namespace RaspiPlant\Component\Sensor;

use RaspiPlant\Component\Device\I2CDevice;

/**
 *
 */
class DHTSensor extends AbstractSensor {

    const DHT_SENSOR_WHITE = 1;

    const DHT_SENSOR_BLUE = 0;

    const DHT_TEMP_CMD = 40;

    /**
     *
     * @var boolean
     */
    protected $debug;

    /**
     *
     * @var int
     */
    protected $type;

    /**
     *
     * @var Device
     */
    protected $device;

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
     * @param I2CDevice $device
     * @param int $pin
     * @param string $name
     * @param boolean $debug
     */
    function __construct(I2CDevice $device, $pin, $name, $type = self::DHT_SENSOR_WHITE, $debug = false) {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->device = $device;
        $this->type = $type;

        $this->device->pinMode($this->pin, "INPUT");
    }

    public function readSensorData() {

        try {

            $this->device->digitalWrite(self::DHT_TEMP_CMD, $this->pin, $this->type, 0);
            usleep(100000);

            $number = $this->device->readBuffer(1, $this->pin, 32);

            $result = array_map ( function($val){return hexdec($val);} , explode(':', $number));

            $h = '';
            for($i = 2; $i < 6; $i++)  {
                $h.=chr($result[$i]);
            }

            // Unpack float
            $t_val=unpack('f*', $h);
            $t = round($t_val[1], 2);

            $h = '';
            for($i = 6; $i < 10; $i++)  {
                $h.=chr($result[$i]);
            }
            // Unpack float
            $hum_val=unpack('f*', $h);
            $hum = round($hum_val[1], 2);

            return json_encode(
                array(
                    'error' => null,
                    'dht_temperature' => $t,
                    'dht_humidity' => $hum
                )
            );

        } catch (\Exception $exc) {

            return json_encode(
                array(
                    'error' => $exc->getMessage(),
                    'dht_temperature' => 0,
                    'dht_humidity' => 0
                )
            );
        }
    }

    /**
     * @return array
     */
    public static function getFields() {
        return  array(
            'dht_temperature' => array('min' => 0, 'max' => 100, 'unit' => 'C°'),
            'dht_humidity' => array('min' => 0, 'max' => 100, 'unit' => '%')
        );
    }

}
