<?php

namespace RaspiPlant\Bundle\DeviceBundle\Protocol;

use RaspiPlant\Component\WiringPi\WiringPi;

/**
 * Class I2CProtocol
 * @package RaspiPlant\Bundle\DeviceBundle\Protocol
 */
class I2CProtocol extends AbstractProtocol
{
    const I2C_SMBUS_BLOCK_MAX = 32;

    const RPI_I2C_ADDRESS = 0x04;

    const DIGITAL_READ_CMD = 1;
    const DIGITAL_WRITE_CMD = 2;
    const ANALOG_READ_CMD = 3;
    const ANALOG_WRITE_CMD = 4;

    const PIN_MODE_CMD = 5;
    const ULTRASONIC_READ_CMD = 7;

    const VERSION_CMD = 8;
    const ACC_XYZ_CMD = 20;
    const RTC_TIME_CMD = 30;

    const DHT_TEMP_CMD = 40;

    const I2C_UNUSED_VALUE = 0;

    /* Command Format
    # digitalRead() command format header
    dRead_cmd = [1]
    # digitalWrite() command format header
    dWrite_cmd = [2]
    # analogRead() command format header
    aRead_cmd = [3]
    # analogWrite() command format header
    aWrite_cmd = [4]
    # pinMode() command format header
    pMode_cmd = [5]
    # Ultrasonic read
    uRead_cmd = [7]
    # Get firmware version
    version_cmd = [8]
    # Accelerometer (+/- 1.5g) read
    acc_xyz_cmd = [20]
    # RTC get time
    rtc_getTime_cmd = [30]
    # DHT Pro sensor temperature
    dht_temp_cmd = [40]
     *
     */

    protected $address;

    /**
     * I2CProtocol constructor.
     * Create an instance of the I2C device at the specified address on the
     * specified I2C bus number.
     *
     * @param $address
     */
    public function __construct($address) {
        $this->address = $address;
    }

    public function readBuffer($cmd, $pin, $length) {

        return WiringPi::wiringPiI2CReadBuffer ($this->address, 1, $cmd, $pin, $length);
    }

    public function writeBuffer($cmd, $value1, $value2, $value3, $value4, $length) {

        return WiringPi::wiringPiI2CWriteBuffer($this->address, $cmd, $value1, $value2, $value3, $value4, $length);
    }

    /**
     * Write an 8-bit value on the bus (without register).
     *
     * @param $v
     */
    public function writeRaw8($v) {
        $value = $v & 0xFF;
        WiringPi::wiringPiI2CWrite($this->address, $value);
    }

    /*
     * Write an 8-bit value to the specified register.
     */
    public function write8($register, $v) {
        //$value = $v & 0xFF;
        WiringPi::wiringPiI2CWriteReg8($this->address, $register, $v);
    }

    /**
     * Write a 16-bit value to the specified register.
     */
    public function write16($register, $v) {
        //$value = $v & 0xFF;
        WiringPi::wiringPiI2CWriteReg16($this->address, $register, $v);
    }

    /**
     * Write bytes to the specified register.
     */
    public function writeBlockData($register, array $values) {
        for ($i = 0; $i < count($values); $i++) {
           $this->write8($register, $values[$i]);
        }
    }

    # Arduino Digital Read
    public function pinMode($pin, $pinMode) {

        $mode = 0;
        if ($pinMode === "OUTPUT") {
            $mode = 1;
        }

        WiringPi::wiringPiI2CWriteBuffer($this->address, 1, 5, $pin, $mode, 0, 4);

        return 1;
    }

    # Arduino Digital Read
    public function digitalRead($cmd, $pin) {

        WiringPi::wiringPiI2CWriteBuffer ($this->address, 1, $cmd, $pin, 0, 0, 4);
        sleep(0.1);
        return WiringPi::wiringPiI2CReadReg8($this->address, 1);

    }

    # Arduino Digital Write
    public function digitalWrite($cmd, $value1, $value2, $value3) {

        WiringPi::wiringPiI2CWriteBuffer($this->address, 1, $cmd, $value1, $value2, $value3, 4);

	    return 1;
    }

    # Arduino Digital Read
    public function analogRead($pin) {

        WiringPi::wiringPiI2CWriteBuffer ($this->address, 1, 3, $pin, 0, 0, 4);
        sleep(0.1);
        $number = WiringPi::wiringPiI2CReadBuffer ($this->address, 1, 0, 0, 4);
        $result = array_map ( function($val){return hexdec($val);} , explode(':', $number));

        return $result[2] * 256 + $result[3];
    }

    # Arduino Digital Write
    public function analogWrite($pin, $value2, $value3) {

        WiringPi::wiringPiI2CWriteBuffer($this->address, 1, 4, $pin, $value2, $value3, 4);

	return 1;
    }

    /**
     * Read a length number of bytes from the specified register.  Results
     * will be returned as a bytearray.
     */
    public function readBlockData($register, $length  = self::I2C_SMBUS_BLOCK_MAX) {
        $data = array();

        for ($i = 0; $i < $length; $i++) {
           $data[] =  $this->readU8($register+$i);
        }

        return $data;
    }

    /**
     * Read a length number of bytes from the specified register.  Results
     * will be returned as a bytearray.
     */
    public function readRawBlockData($length = self::I2C_SMBUS_BLOCK_MAX) {
        $data = array();

        for ($i = 0; $i < $length; $i++) {
           $data[] =  WiringPi::wiringPiI2CRead($this->address) & 0xFF;
        }

        return $data;
    }

    /**
     * Read an 8-bit value on the bus (without register).
     */
    public function readRaw8() {
        return WiringPi::wiringPiI2CRead($this->address) & 0xFF;
    }

    /**
     * Read an unsigned byte from the specified register.
     */
    public function readU8($register) {
        return WiringPi::wiringPiI2CReadReg8($this->address, $register) & 0xFF;
    }

    /**
     * Read a signed byte from the specified register.
     */
    public function readS8($register) {
        $result = $this->readU8($register);
        if ($result > 127) {
            $result -= 256;
        }
        return $result;
    }

    /**
     * Read an unsigned 16-bit value from the specified register, with the
     * specified endianness (default little endian, or least significant byte first).
     */
    public function readU16($register, $little_endian = true) {
        $result = WiringPi::wiringPiI2CReadReg16($this->address, $register);
        /* Swap bytes if using big endian because read_word_data assumes little
         endian on ARM (little endian) systems. */
        if (!$little_endian) {
            $result = (($result << 8) & 0xFF00) + ($result >> 8);
        }

        return $result;
    }

    /**
     * Read a signed 16-bit value from the specified register, with the
     * specified endianness (default little endian, or least significant byte first).
     *
     * @param type $register
     * @param boolean $little_endian
     * @return int
     */
    public function readS16($register, $little_endian = true) {
        $result = $this->readU16($register, $little_endian);
        if ($result > 32767) {
            $result -= 65536;
        }
        return $result;
    }

    /**
     * Read an unsigned 16-bit value from the specified register, in little
     * endian byte order.
     *
     * @param $register
     * @return int
     */
    public function readU16LE($register) {
        return $this->readU16($register, true);
    }

    /**
     * Read an unsigned 16-bit value from the specified register, in big endian byte order.
     *
     * @param $register
     * @return int
     */
    public function readU16BE($register) {
        return $this->readU16($register, false);
    }

    /**
     * Read a signed 16-bit value from the specified register, in little endian byte order.
     *
     * @param $register
     * @return int
     */
    public function readS16LE($register) {
        return $this->readS16($register, true);
    }

    /**
     * Read a signed 16-bit value from the specified register, in big endian byte order.
     *
     * @param $register
     * @return int
     */
    public function readS16BE($register) {
        return $this->readS16($register, false);
    }

    /*******************************************************************************************************************
     * I2C And I2C Address of Seeed Product
     * http://wiki.seeedstudio.com/I2C_And_I2C_Address_of_Seeed_Product/
     *
     * 111020043 	Grove -6-Position DIP Switch 	                                    0x03
     * 111020048 	Grove -5-Way Switch 	                                            0x03
     * 101020085 	Xadow - Multichannel Gas Sensor 	                                0x04
     * 101020088 	Grove - Multichannel Gas Sensor 	                                0x04
     * 105020001 	Grove - I2C Motor Driver 	                                        0x0F
     * 105020001 	Grove -I2C Motor Driver 	                                        0x0F
     * 107020006 	Grove - I2C FM Receiver 	                                        0x10
     * 107020049 	Grove -I2C FM Receiver v1.1 	                                    0x11/0x12
     * 103020133 	Grove -4-Channel SPDT Relay 	                                    0x11/0x12
     * 103020135 	Grove -4-Channel Solid State Relay 	                                0x11/0x12
     * 103020136 	Grove -8-Channel Solid State Relay 	                                0x11/0x12
     * 101020582 	Grove -3-Axis Digital Accelerometer ±16g Ultra-low Power (BMA400) 	0x15(Default)/0x14(Optional)
     * 101020582 	Grove -3-Axis Accelerometer ±16g Ultro-low Power (BMA400) 	        0x15(Default)/0x14(Optional)
     * 101020071 	Grove - 3-Axis Digital Accelerometer(±400g) 	                    0x18
     * 101020556 	Grove -I2C High Accuracy Temperature Sensor (MCP9808) 	            0x18(Default)/0x19~0x1F(Optional)
     * 101020584 	Grove -6-Axis Accelerometer&Gyroscope（BMI088） 	                    0x19(Default)/0x18(Optional)
     * 101020583 	Grove -Step Counter (BMA456) 	                                    0x19(Default)/0x18(Optional)
     * 101020069 	Grove-Q Touch Sensor 	                                            0x1B
     * 101020034 	Grove - 3-Axis Digital Compass 	                                    0x1E
     * 101020081 	Grove - 6-Axis Accelerometer&Compass v2.0 	                        0x1E
     * 101020061 	Grove - 6-Axis Accelerometer&Compass 	                            0x1E
     * 113020006 	Grove - NFC 	                                                    0x24
     * 101020030 	Grove - Digital Light Sensor 	                                    0x29
     * 101020532 	Grove -Time of Flight Distance Sensor (VL53L0X) 	                0x29
     * 101020341 	Grove -I2C Color Sensor v2.0 	                                    0x29/0x52
     * 101020600 	Grove -I2C UV Sensor (VEML6070) 	                                0x38(Data LSB)/0x39(Data MSB)
     * 101020041 	Grove - I2C Color Sensor 	                                        0x39
     * 104030011 	Grove - OLED Display 1.12" 	                                        0x3C
     * 104030008 	Grove - OLED Display 0.96" 	                                        0x3C
     * 101020034 	Grove - 3-Axis Digital Compass 	                                    0x3C
     * 101020452 	Grove -OLED Display 1.12'' V2 	                                    0x3C
     * 104030001 	Grove - LCD RGB Backlight 	                                        0x3C/0x3E/0x62
     * 104020113 	Grove -16 x 2 LCD (Black on Yellow) 	                            0x3E
     * 104020112 	Grove -16 x 2 LCD (Black on Red) 	                                0x3E
     * 104020111 	Grove -16 x 2 LCD (White on Blue) 	                                0x3E
     * 101020074 	Grove - Temperature&Humidity Sensor (High-Accuracy & Mini) 	        0x40
     * 101020212 	Grove -Temp&Humi Sensor(SHT31) 	                                    0x44
     * 101020089 	Grove -Sunlight Sensor 	                                            0X45
     * 101020091 	Grove -Mini Track ball 	                                            0X4A
     * 101020039 	Grove - 3-Axis Digital Accelerometer(±1.5g) 	                    0x4C
     * 102020083 	Grove -High Precision RTC 	                                        0x51
     * 101020054 	Grove - 3-Axis Digital Accelerometer(±16g) 	                        0x53
     * 101020070 	Grove - NFC Tag 	                                                0x53/0x57
     * 103020013 	Grove - I2C ADC 	                                                0x55
     * 101020512 	Grove -VOC and eCO2 Gas Sensor (SGP30) 	                            0X58
     * 105020011 	Grove -Haptic Motor 	                                            0x5A
     * 101020047 	Grove - I2C Touch Sensor 	                                        0x5A/0x5B/0x5C/0x5D
     * 101020077 	Grove - Digital Infrared Temperature Sensor 	                    0x5B
     * 101020534 	Grove -12 Key Capacitive I2C Touch Sensor V2 (MPR121) 	            0x5B(Default)/0x5C/0x5D
     * 101020594 	Grove -I2C Thermocouple Amplifier (MCP9600) 	                    0x60(Default)/0x67(Optional)
     * 104030001 	Grove - LCD RGB Backlight 	                                        0x62
     * 101020013 	Grove - RTC 	                                                    0x68
     * 101020050 	Grove - 3-Axis Digital Gyro 	                                    0x68
     * 101020557 	Grove -Infrared Temperature Sensor Array (AMG8833) 	                0x68(Default)/0x69(Optional)
     * 101020080 	Grove -IMU 9DOF v2.0 	                                            0x68/0x69
     * 101020252 	Grove -IMU 10DOF v2.0 	                                            0x68/0x69
     * 101020059 	Grove - IMU 9DOF 	                                                0x68/0x69/0x77
     * 101020079 	Grove - IMU 10DOF 	                                                0x68/0x77
     * 101020585 	Grove -IMU 9DOF (ICM20600 & AK09918) 	                            0x69(Default)/0x68(Optional)
     * 101020083 	Grove - Gesture（PAJ7620U2） 	                                        0x73
     * 101020068 	Grove - Barometer (High-Accuracy) 	                                0x76
     * 101020513 	Grove -Temperature, Humidity, Pressure and Gas Sensor (BME680) 	    0x76(Default)/ 0x77(Optional)
     * 101020032 	Grove - Barometer Sensor 	                                        0x77
     * 101020072 	Grove - Barometer Sensor (BMP180) 	                                0x77
     * 103020024 	Grove -Finger-clip Heart Rate Sensor 	                            0xA0
     * 105020010 	Grove -I2C Mini Motor Driver 	                                    Default: CH1 Write(0xC4), CH1 Read(0xC5);CH2 Write(0xC0), CH2 Read(0xC1); Optional:CH1 Write(0xD0), CH1 Read(0xD1); CH2 Write(0xCC), CH2 Read(0xCD);
     * 103020009 	Grove -Nunchuck 	                                                NA
     *******************************************************************************************************************/
}
