<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

class BMP280Sensor extends AbstractSensor {
    
    /*
     * BMP280 default address.
     */
    const BMP280_I2CADDR = 0x77;
    const BMP280_CHIPID = 0xD0;

    /* BMP280 Registers */
    const BMP280_DIG_T1 = 0x88;  // R   Unsigned Calibration data (16 bits)
    const BMP280_DIG_T2 = 0x8A;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_T3 = 0x8C;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P1 = 0x8E;  // R   Unsigned Calibration data (16 bits)
    const BMP280_DIG_P2 = 0x90;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P3 = 0x92;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P4 = 0x94;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P5 = 0x96;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P6 = 0x98;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P7 = 0x9A;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P8 = 0x9C;  // R   Signed Calibration data (16 bits)
    const BMP280_DIG_P9 = 0x9E;  // R   Signed Calibration data (16 bits)
    const BMP280_CONTROL = 0xF4;
    const BMP280_RESET = 0xE0;
    const BMP280_CONFIG = 0xF5;
    const BMP280_PRESSUREDATA = 0xF7;
    const BMP280_TEMPDATA = 0xFA;

    protected $fd;
    protected $device;
    protected $cal_t1;
    protected $cal_t2;
    protected $cal_t3;
    protected $cal_p1;
    protected $cal_p2;
    protected $cal_p3;
    protected $cal_p4;
    protected $cal_p5;
    protected $cal_p6;
    protected $cal_p7;
    protected $cal_p8;
    protected $cal_p9;

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
     * 
     * @param I2CDevice $device
     * @param int $pin
     * @param string $name
     * @param boolean $debug
     */
    public function __construct(I2CDevice $device, $pin, $name, $debug = false) {

        $this->debug = $debug;

        $this->device = $device;
        $this->pin = $pin;
        $this->name = $name;

        if ($this->device->readU8(self::BMP280_CHIPID) != 0x58) {
            throw new \Exception("Unsupported chip!!");
        }

        $calibrate = $this->_loadCalibration();

        if ($calibrate && $this->debug) {
            dump($calibrate);
        }

        $this->device->write8(self::BMP280_CONTROL, 0x3F);
    }

    protected function _loadCalibration() {

        $this->cal_t1 = (int) $this->device->readU16(self::BMP280_DIG_T1);
        $this->cal_t2 = (int) $this->device->readS16(self::BMP280_DIG_T2);
        $this->cal_t3 = (int) $this->device->readS16(self::BMP280_DIG_T3);
        $this->cal_p1 = (int) $this->device->readU16(self::BMP280_DIG_P1);
        $this->cal_p2 = (int) $this->device->readS16(self::BMP280_DIG_P2);
        $this->cal_p3 = (int) $this->device->readS16(self::BMP280_DIG_P3);
        $this->cal_p4 = (int) $this->device->readS16(self::BMP280_DIG_P4);
        $this->cal_p5 = (int) $this->device->readS16(self::BMP280_DIG_P5);
        $this->cal_p6 = (int) $this->device->readS16(self::BMP280_DIG_P6);
        $this->cal_p7 = (int) $this->device->readS16(self::BMP280_DIG_P7);
        $this->cal_p8 = (int) $this->device->readS16(self::BMP280_DIG_P8);
        $this->cal_p9 = (int) $this->device->readS16(self::BMP280_DIG_P9);

        return json_encode(
                array(
                    't1' => $this->cal_t1,
                    't2' => $this->cal_t2,
                    't3' => $this->cal_t3,
                    'p1' => $this->cal_p1,
                    'p2' => $this->cal_p2,
                    'p3' => $this->cal_p3,
                    'p4' => $this->cal_p4,
                    'p5' => $this->cal_p5,
                    'p6' => $this->cal_p6,
                    'p7' => $this->cal_p7,
                    'p8' => $this->cal_p8,
                    'p9' => $this->cal_p9,
                )
        );
    }

    protected function _loadFixtureCalibration() {

        $this->cal_t1 = 27504;
        $this->cal_t2 = 26435;
        $this->cal_t3 = -1000;
        $this->cal_p1 = 36477;
        $this->cal_p2 = -10685;
        $this->cal_p3 = 3024;
        $this->cal_p4 = 2855;
        $this->cal_p5 = 140;
        $this->cal_p6 = -7;
        $this->cal_p7 = 15500;
        $this->cal_p8 = -14500;
        $this->cal_p9 = 6000;
    }

    protected function readRaw($register) {
        $raw = $this->device->readU16BE($register);
        $raw <<= 8;
        $raw = $raw | $this->device->readU8($register + 2);
        $raw >>= 4;
        
        /** Reads the raw (uncompensated) temperature or pressure from the sensor.* */
        return $raw;
    }

    /**
     * Compensate temperature
     * @param int $rawTemp
     */
    protected function _compensateTemp($rawTemp) {
        $t1 = ((($rawTemp >> 3) - ($this->cal_t1 << 1)) * ($this->cal_t2)) >> 11;
        $t2 = ((((($rawTemp >> 4) - ($this->cal_t1)) * (($rawTemp >> 4) - ($this->cal_t1))) >> 12) * ($this->cal_t3)) >> 14;

        return $t1 + $t2;
    }

    protected function readTemperature() {
        $rawTemperature = $this->readRaw(self::BMP280_TEMPDATA);
        /* Gets the compensated temperature in degrees celsius. */
        $compensatedTemp = $this->_compensateTemp($rawTemperature);
        $temp = floatval(($compensatedTemp * 5 + 128) >> 8) / 100;
        return $temp;
    }

    public function readPressure() {
        
        //@TODO clean all that shit and spread bcMath usage all over ;) 
        $rawTemp = $this->readRaw(self::BMP280_TEMPDATA);
        $compensatedTemp = $this->_compensateTemp($rawTemp);
        $rawPressure = $this->readRaw(self::BMP280_PRESSUREDATA);
        $p1 = $compensatedTemp - 128000;
        //Since raspberry php 
        $p2 = bcadd(bcadd(bcmul(bcmul($p1, $p1), $this->cal_p6), bcmul(bcmul($p1, $this->cal_p5), bcpow(2, 17))), bcmul($this->cal_p4, bcpow(2, 35)));
        $p1 = bcadd(bcmul(bcdiv(bcmul($p1,$p1),$this->cal_p3),bcpow(2, 8)), bcmul(bcmul($p1,$this->cal_p2), bcpow(2, 12)));
        $p1 = bcdiv(bcmul(bcadd(bcmul(1, bcpow(2, 47)), $p1), $this->cal_p1), bcpow(2, 33));
        
        if ('0' == $p1) {
            return 0;
        }

        $p = 1048576 - $rawPressure;
        $p = bcdiv(bcmul(bcsub(bcmul($p, bcpow(2, 31)), $p2), 3125), $p1);
        $y = bcmul(bcdiv($p, bcpow(2, 13)),bcdiv($p,bcpow(2, 13)));
        $z = bcmul($this->cal_p9, $y);
        $p1 = bcdiv($z, bcpow(2, 25));
        $p2 = bcdiv(bcmul($this->cal_p8, $p), bcpow(2, 19));
        $p = bcadd(bcdiv(bcadd(bcadd($p, $p1), $p2), bcpow(2,8)), bcmul($this->cal_p7,bcpow(2,4)));
     
        return floatval(intval($p) / 256);
    }

    protected function readAltitude($sealevelPa = 101325.0) {
        /*         * Calculates the altitude in meters.* */
        //Calculation taken straight from section 3.6 of the datasheet.
        $pressure = $this->readPressure();
        $altitude = 44330.0 * (1.0 - pow($pressure / $sealevelPa, (1.0 / 5.255)));

        return $altitude;
    }

    protected function readSealevelPressure($altitudeM = 0.0) {
        /*         * Calculates the pressure at sealevel when given a known altitude in
          meters. Returns a value in Pascals.** */
        $pressure = $this->readPressure();
        $p0 = $pressure / pow((1.0 - $altitudeM) / 44330.0, 5.255);
        return $p0;
    }

    public function readSensorData() {

        return json_encode(
                array(
                    'bmp280_temperature' => $this->readTemperature(),
                    'bmp280_pressure' => $this->readPressure()/100,
                    'bmp280_altitude' => $this->readAltitude()
                )
        );
    }

    /**
     * @return array
     */
    public static function getfields() {
        return array(
            'bmp280_temperature' => array('min' => 0, 'max' => 100, 'unit' => 'C°'),
            'bmp280_pressure' => array('min' => 0, 'max' => 100, 'unit' => 'mBar'),
            'bmp280_altitude' => array('min' => 0, 'max' => 100, 'unit' => 'm')
        );
    }

}
