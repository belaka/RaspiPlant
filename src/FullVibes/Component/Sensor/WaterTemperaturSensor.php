<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\W1Device;

/**
 * 
 */
class WaterTemperaturSensor extends AbstractSensor {

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
     * @param W1Device $device
     * @param int $pin
     * @param string $name
     * @param boolean $debug
     */
    function __construct(W1Device $device, $pin, $name, $debug = false) {
        
        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->device = $device;
    }

    public function readSensorData() {
        		
        try {

            $deviceId = '28-0000073b00e1';
            $tString = $this->device->readAddress($deviceId);

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
        $celsius = round($matches[1] / 1000); //round the results

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
