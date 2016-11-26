<?php

namespace FullVibes\Component\Sensor;

use FullVibes\Component\Device\I2CDevice;

class BME280Sensor extends AbstractSensor {
    /*
     * BME280 default address.
     */

    const BME280_ADDRESS = 0x76;

    /*
     * BME280 temperature registry.
     */
    const BME280_REG_DIG_T1 = 0x88; // R   Unsigned Calibration data (16 bits)
    const BME280_REG_DIG_T2 = 0x8a; // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_T3 = 0x8C; // R   Signed Calibration data (16 bits)

    /*
     * BME280 pressure registry.
     */
    const BME280_REG_DIG_P1 = 0x8E;   // R   Unsigned Calibration data (16 bits)
    const BME280_REG_DIG_P2 = 0x90;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_P3 = 0x92;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_P4 = 0x94;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_P5 = 0x96;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_P6 = 0x98;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_P7 = 0x9A;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_P8 = 0x9C;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_P9 = 0x9E;   // R   Signed Calibration data (16 bits)

    /*
     * BME280 humidity registry.
     */
    const BME280_REG_DIG_H1 = 0xA1;   // R   Unsigned Calibration data (8 bits)
    const BME280_REG_DIG_H2 = 0xE1;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_H3 = 0xE3;   // R   Signed Calibration data (8 bits)
    const BME280_REG_DIG_H4 = 0xE4;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_H5 = 0xE5;   // R   Signed Calibration data (16 bits)
    const BME280_REG_DIG_H6 = 0xE7;   // R   Signed Calibration data (8 bits)

    /*
     * BME280 service registry.
     */
    #define BME280_REG_CHIPID          0xD0
    const BME280_REG_CHIPID = 0xD0;
    #define BME280_REG_VERSION         0xD1
    const BME280_REG_VERSION = 0xD1;
    #define BME280_REG_SOFTRESET       0xE0
    const BME280_REG_SOFTRESET = 0xE0;

    /*
     * BME280 control registry.
     */
    const BME280_REG_CAL26 = 0xE1;
    const BME280_REG_CONTROLHUMID = 0xF2;
    const BME280_REG_CONTROL = 0xF4;
    const BME280_REG_CONFIG = 0xF5;
    const BME280_REG_PRESSUREDATA = 0xF7;
    const BME280_REG_TEMPDATA = 0xFA;
    const BME280_REG_HUMIDITYDATA = 0xFD;

    //Device
    protected $fd;
    protected $device;
    //Data
    protected $dig_t1;
    protected $dig_t2;
    protected $dig_t3;
    protected $dig_p1;
    protected $dig_p2;
    protected $dig_p3;
    protected $dig_p4;
    protected $dig_p5;
    protected $dig_p6;
    protected $dig_p7;
    protected $dig_p8;
    protected $dig_p9;
    protected $dig_h1;
    protected $dig_h2;
    protected $dig_h3;
    protected $dig_h4;
    protected $dig_h5;
    protected $dig_h6;

    /**
     * int32
     */
    protected $t_fine;
    protected $temperature;
    protected $humidity;
    protected $altitude;
    protected $pressure;

    /**
     *
     * @var boolean
     */
    protected $debug;

    public function __construct($device, $debug = false) {

        $this->debug = $debug;
        $this->device = $device;

        if ($this->device->readU8(self::BME280_REG_CHIPID) != 0x60) {
            throw new \Exception("Unsupported chip!!");
        }
    }

    protected function init() {

        $this->t_fine = null;
        $this->temperature = null;
        $this->humidity = null;
        $this->altitude = null;
        $this->pressure = null;

        $this->dig_t1 = $this->device->readU16LE(self::BME280_REG_DIG_T1);
        $this->dig_t2 = $this->device->readS16LE(self::BME280_REG_DIG_T2);
        $this->dig_t3 = $this->device->readS16LE(self::BME280_REG_DIG_T3);

        $this->dig_p1 = $this->device->readU16LE(self::BME280_REG_DIG_P1);
        $this->dig_p2 = $this->device->readS16LE(self::BME280_REG_DIG_P2);
        $this->dig_p3 = $this->device->readS16LE(self::BME280_REG_DIG_P3);
        $this->dig_p4 = $this->device->readS16LE(self::BME280_REG_DIG_P4);
        $this->dig_p5 = $this->device->readS16LE(self::BME280_REG_DIG_P5);
        $this->dig_p6 = $this->device->readS16LE(self::BME280_REG_DIG_P6);
        $this->dig_p7 = $this->device->readS16LE(self::BME280_REG_DIG_P7);
        $this->dig_p8 = $this->device->readS16LE(self::BME280_REG_DIG_P8);
        $this->dig_p9 = $this->device->readS16LE(self::BME280_REG_DIG_P9);

        $this->dig_h1 = $this->device->readU8(self::BME280_REG_DIG_H1);
        $this->dig_h2 = $this->device->readU16LE(self::BME280_REG_DIG_H2);
        $this->dig_h3 = $this->device->readU8(self::BME280_REG_DIG_H3);
        $this->dig_h4 = ($this->device->readU8(self::BME280_REG_DIG_H4) << 4) | (0x0F & $this->device->readU8(self::BME280_REG_DIG_H4 + 1));
        $this->dig_h5 = ($this->device->readU8(self::BME280_REG_DIG_H5 + 1) << 4) | (0x0F & $this->device->readU8(self::BME280_REG_DIG_H5) >> 4);
        $this->dig_h6 = $this->device->readU8(self::BME280_REG_DIG_H6);

        //writeRegister
        $this->device->write8(self::BME280_REG_CONTROLHUMID, 0x05);  //Choose 16X oversampling
        $this->device->write8(self::BME280_REG_CONTROL, 0xB7);  //Choose 16X oversampling
    }

    /*     * *************************************************************************

      float BME280::getTemperature(void)
      {
      int32_t var1, var2;

      int32_t adc_T = BME280Read24(BME280_REG_TEMPDATA);
      adc_T >>= 4;
      var1 = (((adc_T >> 3) - ((int32_t)(dig_T1 << 1))) *
      ((int32_t)dig_T2)) >> 11;

      var2 = (((((adc_T >> 4) - ((int32_t)dig_T1)) *
      ((adc_T >> 4) - ((int32_t)dig_T1))) >> 12) *
      ((int32_t)dig_T3)) >> 14;

      t_fine = var1 + var2;
      float T = (t_fine * 5 + 128) >> 8;
      return T/100;
      }

      uint32_t BME280::getPressure(void)
      {
      int64_t var1, var2, p;

      // Call getTemperature to get t_fine
      getTemperature();

      int32_t adc_P = BME280Read24(BME280_REG_PRESSUREDATA);
      adc_P >>= 4;

      var1 = ((int64_t)t_fine) - 128000;
      var2 = var1 * var1 * (int64_t)dig_P6;
      var2 = var2 + ((var1*(int64_t)dig_P5)<<17);
      var2 = var2 + (((int64_t)dig_P4)<<35);
      var1 = ((var1 * var1 * (int64_t)dig_P3)>>8) + ((var1 * (int64_t)dig_P2)<<12);
      var1 = (((((int64_t)1)<<47)+var1))*((int64_t)dig_P1)>>33;
      if (var1 == 0)
      {
      return 0; // avoid exception caused by division by zero
      }
      p = 1048576-adc_P;
      p = (((p<<31)-var2)*3125)/var1;
      var1 = (((int64_t)dig_P9) * (p>>13) * (p>>13)) >> 25;
      var2 = (((int64_t)dig_P8) * p) >> 19;
      p = ((p + var1 + var2) >> 8) + (((int64_t)dig_P7)<<4);
      return (uint32_t)p/256;
      }

      uint32_t BME280::getHumidity(void)
      {
      int32_t v_x1_u32r, adc_H;

      // Call getTemperature to get t_fine
      getTemperature();

      adc_H = BME280Read16(BME280_REG_HUMIDITYDATA);

      v_x1_u32r = (t_fine - ((int32_t)76800));
      v_x1_u32r = (((((adc_H << 14) - (((int32_t)dig_H4) << 20) - (((int32_t)dig_H5) * v_x1_u32r)) + ((int32_t)16384)) >> 15) * (((((((v_x1_u32r * ((int32_t)dig_H6)) >> 10) * (((v_x1_u32r * ((int32_t)dig_H3)) >> 11) + ((int32_t)32768))) >> 10) + ((int32_t)2097152)) * ((int32_t)dig_H2) + 8192) >> 14));
      v_x1_u32r = (v_x1_u32r - (((((v_x1_u32r >> 15) * (v_x1_u32r >> 15)) >> 7) * ((int32_t)dig_H1)) >> 4));
      v_x1_u32r = (v_x1_u32r < 0 ? 0 : v_x1_u32r);
      v_x1_u32r = (v_x1_u32r > 419430400 ? 419430400 : v_x1_u32r);
      return (uint32_t)(v_x1_u32r>>12)/1024.0;
      }

      float BME280::calcAltitude(float pressure)
      {
      float A = pressure/101325;
      float B = 1/5.25588;
      float C = pow(A,B);
      C = 1.0 - C;
      C = C /0.0000225577;
      return C;
      }

     * ************************************************************************* */

    protected function getTemperature() {
        
        if (!is_null($this->temperature))  {
            return $this->temperature;
        }
        
        $rawTemperature = $this->readRaw(self::BME280_REG_TEMPDATA);
        
        /* Gets the compensated temperature in degrees celsius. */
        $this->t_fine = $this->_compensateTemp($rawTemperature);
        $this->temperature = floatval(($this->t_fine * 5 + 128) >> 8) / 100;
        
        return $this->temperature;
    }

    /**
     * Compensate temperature
     * @param int $rawTemp
     */
    protected function _compensateTemp($rawTemp) {
        $t1 = ((($rawTemp >> 3) - ($this->dig_t1 << 1)) * ($this->dig_t2)) >> 11;
        $t2 = ((((($rawTemp >> 4) - ($this->dig_t1)) * (($rawTemp >> 4) - ($this->dig_t1))) >> 12) * ($this->dig_t3)) >> 14;

        return $t1 + $t2;
    }

    protected function readHumidity() {
        
        if (!is_null($this->humidity))  {
            return $this->humidity;
        }

        if (is_null($this->temperature))  {
            // Call getTemperature to get t_fine
            $this->getTemperature();
        }
        

        $adc_H = $this->device->readU16(self::BME280_REG_HUMIDITYDATA);

        $v_x1_u32r = ($this->t_fine - (76800)); //(int32_t)
        $v_x1_u32r = ((((($adc_H << 14) - (($this->dig_h4) << 20) - (($this->dig_h5) * $v_x1_u32r)) + (16384)) >> 15) * ((((((($v_x1_u32r * ($this->dig_h6)) >> 10) * ((($v_x1_u32r * ($this->dig_h3)) >> 11) + (32768))) >> 10) + (2097152)) * ($this->dig_h2) + 8192) >> 14));
        $v_x1_u32r = ($v_x1_u32r - ((((($v_x1_u32r >> 15) * ($v_x1_u32r >> 15)) >> 7) * ($this->dig_h1)) >> 4));
        $v_x1_u32r = ($v_x1_u32r < 0 ? 0 : $v_x1_u32r);
        $v_x1_u32r = ($v_x1_u32r > 419430400 ? 419430400 : $v_x1_u32r);
        
        $this->humidity =  ($v_x1_u32r >> 12) / 1024.0; // (uint32_t)
        
        return $this->humidity;
    }

    public function getPressure() {

        if (!is_null($this->pressure))  {
            return $this->pressure;
        }
        
        //@TODO clean all that shit and spread bcMath usage all over ;) 
        $rawTemp = $this->readRaw(self::BME280_REG_TEMPDATA);
        $compensatedTemp = $this->_compensateTemp($rawTemp);
        $rawPressure = $this->readRaw(self::BME280_REG_PRESSUREDATA);
        $p1 = $compensatedTemp - 128000;
        //Since raspberry php 
        $p2 = bcadd(bcadd(bcmul(bcmul($p1, $p1), $this->dig_p6), bcmul(bcmul($p1, $this->dig_p5), bcpow(2, 17))), bcmul($this->dig_p4, bcpow(2, 35)));
        $p1 = bcadd(bcmul(bcdiv(bcmul($p1, $p1), $this->dig_p3), bcpow(2, 8)), bcmul(bcmul($p1, $this->dig_p2), bcpow(2, 12)));
        $p1 = bcdiv(bcmul(bcadd(bcmul(1, bcpow(2, 47)), $p1), $this->dig_p1), bcpow(2, 33));

        if ('0' == $p1) {
            return 0;
        }

        $p = 1048576 - $rawPressure;
        $p = bcdiv(bcmul(bcsub(bcmul($p, bcpow(2, 31)), $p2), 3125), $p1);
        $y = bcmul(bcdiv($p, bcpow(2, 13)), bcdiv($p, bcpow(2, 13)));
        $z = bcmul($this->dig_p9, $y);
        $p1 = bcdiv($z, bcpow(2, 25));
        $p2 = bcdiv(bcmul($this->dig_p8, $p), bcpow(2, 19));
        $p = bcadd(bcdiv(bcadd(bcadd($p, $p1), $p2), bcpow(2, 8)), bcmul($this->dig_p7, bcpow(2, 4)));

        $this->pressure = floatval(intval($p) / 256);
        
        return $this->pressure;
    }

    protected function readAltitude($sealevelPa = 101325.0) {
        
        if (!is_null($this->altitude))  {
            return $this->altitude;
        }
        
        /* 
         * Calculates the altitude in meters.
         * 
         */
        //Calculation taken straight from section 3.6 of the datasheet.
        $pressure = $this->getPressure();
        $this->altitude = 44330.0 * (1.0 - pow($pressure / $sealevelPa, (1.0 / 5.25588)));

        return $this->altitude / 0.0000225577;
    }

    protected function getSealevelPressure($altitudeM = 0.0) {

        /*
         *  Calculates the pressure at sealevel when given a known altitude in
         *  meters. Returns a value in Pascals.
         * 
         */
        $pressure = $this->getPressure();
        $p0 = $pressure / pow((1.0 - $altitudeM) / 44330.0, 5.25588);
        
        return $p0;
    }
    
    protected function readRaw($register) {
        $raw = $this->device->readU16BE($register);
        $raw <<= 8;
        $raw = $raw | $this->device->readU8($register + 2);
        $raw >>= 4;
        
        /** Reads the raw (uncompensated) temperature or pressure from the sensor.* */
        return $raw;
    }

    public function readSensorData() {

        $this->init();

        return json_encode(
                array(
                    'temperature' => $this->getTemperature(),
                    'pressure' => $this->getPressure() / 100,
                    'humidity' => $this->getHumidity(),
                    'altitude' => $this->getAltitude(),
                    'sea_level_pressure' => $this->getSealevelPressure(),
                    'dew_point' => 0
                )
        );
    }

}
