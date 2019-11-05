<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

/**
 *
 */
class DHTSensor extends AbstractSensor
{

    const DHT_SENSOR_WHITE = 1;

    const DHT_SENSOR_BLUE = 0;

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
     * DHTSensor constructor.
     * @param I2CProtocol $protocol
     * @param $pin
     * @param $name
     * @param int $type
     * @param bool $debug
     */
    function __construct(I2CProtocol $protocol, $pin, $name, $type = self::DHT_SENSOR_WHITE, $debug = false)
    {

        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->protocol = $protocol;
        $this->type = $type;

        $this->protocol->pinMode($this->pin, "INPUT");
    }

    /**
     * @return array
     */
    public static function getFields()
    {
        return array(
            'dht_temperature' => array('min' => 0, 'max' => 100, 'unit' => 'C°'),
            'dht_humidity' => array('min' => 0, 'max' => 100, 'unit' => '%')
        );
    }

    public function readSensorData()
    {

        try {

            $this->protocol->digitalWrite(I2CProtocol::DHT_TEMP_CMD, $this->pin, $this->type, 0);
            usleep(100000);

            $number = $this->protocol->readBuffer(1, $this->pin, 32);

            $result = array_map(function ($val) {
                return hexdec($val);
            }, explode(':', $number));

            $h = '';
            for ($i = 2; $i < 6; $i++) {
                $h .= chr($result[$i]);
            }

            // Unpack float
            $t_val = unpack('f*', $h);
            $t = round($t_val[1], 2);

            $h = '';
            for ($i = 6; $i < 10; $i++) {
                $h .= chr($result[$i]);
            }
            // Unpack float
            $hum_val = unpack('f*', $h);
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

}
