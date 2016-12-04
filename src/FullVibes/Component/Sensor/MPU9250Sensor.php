<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

/**
 * 
 */
class MPU9250Sensor extends AbstractSensor {

    /**
     * I2C HEX ADDRESS
     */
    const MPU9250_I2C_ADDR = 0x68;

    /**
     * SENSOR FREQUENCY
     */
    const FREQ = 30;   //sample freq in Hz

    /**
     * I2C Device Object
     * @var I2CDevice
     */

    protected $device;

    /**
     *
     * @var int 
     */
    protected $accX = 0;

    /**
     *
     * @var int 
     */
    protected $accY = 0;

    /**
     *
     * @var int
     */
    protected $accZ = 0;

    /**
     *
     * @var int
     */
    protected $gyroX = 0;

    /**
     *
     * @var int
     */
    protected $gyroY = 0;

    /**
     *
     * @var int
     */
    protected $gyroZ = 0;

    /**
     *
     * @var int
     */
    protected $tempOut = 0;

    /**
     * the X offset of gyro
     * @var int
     */
    protected $gyrXoffs = 0;

    /**
     * the Y offset of gyro
     * @var int 
     */
    protected $gyrYoffs = 0;

    /**
     * the Z offset of gyro
     * @var int
     */
    protected $gyrZoffs = 0;
    
    /**
     *
     * @var boolean
     */
    protected $debug;

    function __construct($debug = false) {

        $this->debug = $debug;
        
        $fd = wiringpii2csetup(self::MPU9250_I2C_ADDR);
        $this->device = new I2CDevice($fd);

        $this->device->write8(0x6b, 0x00);
        $this->device->write8(0x1a, 0x01);
        $this->device->write8(0x1b, 0x08);

        /*         * *********************************************************************
          #CONFIG: set sample rate, sample rate FREQ=Gyro sample rate / (sample_div + 1)
          #1KHz / (div + 1) = FREQ
          #reg_calue = 1KHz/FREQ - 1
          #sample_div = 1000 / self.FREQ - 1
          #bus.write_byte_data(self.MPU9250_I2C_ADDR, 0x19, chr(sample_div))
          bus.write_byte_data(self.MPU9250_I2C_ADDR, 0x19, 32)
         * 
         * ******************************************************************** */

        $calibrate = $this->calibrateGyro();
        
        if ($calibrate && $this->debug)  {
            dump($calibrate);
        }
    }

    protected function calibrateGyro() {

        $xSum = 0;
        $ySum = 0;
        $zSum = 0;
        $cnt = 500;

        try {
            for ($i = 0; $i < $cnt; $i++) {
                $data = $this->device->readBlockData(0x43);
                if ($this->debug)  {
                    dump($data);
                }
                $xSum = $xSum + ($data[0] << 8 | $data[1]);
                $ySum = $xSum + ($data[2] << 8 | $data[3]);
                $zSum = $xSum + ($data[4] << 8 | $data[5]);
            }

            $this->gyrXoffs = $xSum / $cnt;
            $this->gyrYoffs = $ySum / $cnt;
            $this->gyrZoffs = $zSum / $cnt;

            return json_encode(
                    array(
                        'gyrXoffs' => $this->gyrXoffs,
                        'gyrYoffs' => $this->gyrYoffs,
                        'gyrZoffs' => $this->gyrZoffs,
                    )
            );
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
            exit();
        }
    }

    public function readSensorData() {
        try {
            $data = $this->device->readBlockData(0x3B);
            print_r($data);
            if ($this->debug)  {
                dump($data);
            }
            $this->accX = $data[0] << 8 | $data[1];
            $this->accY = $data[2] << 8 | $data[3];
            $this->accZ = $data[4] << 8 | $data[5];

            $this->tempOut = $data[6] << 8 | $data[7];

            $this->gyroX = $data[8] << 8 | $data[9] - $this->gyrXoffs;
            $this->gyroY = $data[10] << 8 | $data[11] - $this->gyrYoffs;
            $this->gyroZ = $data[12] << 8 | $data[13] - $this->gyrZoffs;

            return json_encode(
                    array(
                        'time' => time(),
                        'tempOut' => $this->tempOut,
                        'gyroX' => $this->gyroX,
                        'gyroY' => $this->gyroY,
                        'gyroZ' => $this->gyroZ,
                        'accX' => $this->accX,
                        'accY' => $this->accY,
                        'accZ' => $this->accZ
                    )
            );
        } catch (\Exception $exc) {
            echo $exc->getTraceAsString();
            exit();
        }
    }
    
    public static function getFields() {
        return array(
            'time',
            'tempOut',
            'gyroX',
            'gyroY',
            'gyroZ',
            'accX',
            'accY',
            'accZ'
        );
    }

}
