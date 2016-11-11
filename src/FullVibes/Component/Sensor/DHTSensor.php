<?php

namespace FullVibes\Component\Sensor;

/**
 * 
 */
class DHTSensor extends AbstractSensor {

    const DHT_SENSOR_WHITE = 1;
    
    const DHT_SENSOR_BLUE = 0;
    
    const DHT_TEMP_CMD = 40;
    
    const DHT_TEMPERATURE_MIN_VALUE = 0;
    
    const DHT_TEMPERATURE_MAX_VALUE = 90;
    
    const DHT_HUMIDITY_MIN_VALUE = 0;
    
    const ADHT_HUMIDITY_MAX_VALUE = 100;
    
    const DHT_KEYS = array('temperature','humidity');
    
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
    
    function __construct($device, $pin, $type = self::DHT_SENSOR_WHITE, $debug = false) {
        
        $this->debug = $debug;
        $this->type = $type;
        $this->pin = $pin;
        $this->device = $device;
        
    }

    public function readSensorData() {
        		
        try {
            
            $this->device->pinMode($this->pin, "OUTPUT");
            usleep(1800);
            $this->device->digitalWrite(self::DHT_TEMP_CMD, $this->pin, $this->type, 0);
            
//            usleep(20000);
//            
//            $this->device->pinMode($this->pin, "INPUT");
            usleep(1800);
            $number = $this->device->readBuffer($this->pin, 0, 32);
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
            
            return json_encode(array('temperature' => $t, 'humidity' => $hum));
            
        } catch (\Exception $exc) {
            
            return json_encode(array('temperature' => $exc->getMessage(), 'humidity' => $exc->getMessage()));
        }
    }
    
    public function getRange()
    {
        return array(
            'temperature' => array(
                'min' => self::DHT_TEMPERATURE_MIN_VALUE,
                'max' => self::DHT_TEMPERATURE_MAX_VALUE
            ),
            'humidity' => array(
                'min' => self::DHT_HUMIDITY_MIN_VALUE,
                'max' => self::DHT_HUMIDITY_MAX_VALUE
            )
        );
    }
    
    public function getKeys()
    {
        return self::DHT_KEYS;
    }
}
