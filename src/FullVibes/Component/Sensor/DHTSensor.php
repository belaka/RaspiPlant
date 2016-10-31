<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

/**
 * 
 */
class DHTSensor extends AbstractSensor {

    const DHT_SENSOR_WHITE = 1;
    
    const DHT_SENSOR_BLUE = 0;
    
    const RPI_I2C_ADDRESS = 0x04; // I2C Address of Raspberry
    
    const I2C_UNUSED_VALUE = 0;
    
    const DHT_TEMP_CMD = 40;
    
    const LOW = 0;
    const HIGH = 1;
    const INPUT = 0;
    const OUTPUT = 1;

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
     * @var type 
     */
    protected $fd;
    
    /**
     *
     * @var int
     */
    protected $pin;
    
    function __construct($pin, $type = self::DHT_SENSOR_WHITE, $debug = false) {
        
        $this->debug = $debug;
        $this->type = $type;
        $this->pin = $pin;
        
        $this->fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $this->device = new I2CDevice($this->fd);
        
    }

    public function readSensorData() {
        		
        try {
            
            $this->device->digitalWrite(self::DHT_TEMP_CMD, $this->pin, $this->type, 0);
            
            $number = wiringPiI2CReadBuffer ($this->fd, $this->pin, 0, 0, 32);
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
}
