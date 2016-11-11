<?php

namespace FullVibes\Component\Device;

/**
 * Class for communicating with an I2C device using the wiringPi I2C interface. 
 * Allows reading and writing 8-bit, 16-bit, and byte array values to registers
 * on the device.
 *
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class I2CDevice extends AbstractDevice
{
    const I2C_SMBUS_BLOCK_MAX = 32;
    
    const RPI_I2C_ADDRESS = 0x04;
    
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
     * Create an instance of the I2C device at the specified address on the
     *   specified I2C bus number.
     */
    public function __construct($address) {
        $this->address = $address;
    }
    
    public function readBuffer($value1, $value2, $value3, $length) {
        return wiringPiI2CReadBuffer ($this->address, $value1, $value2, $value3, $length);
    }

    /**
     * Write an 8-bit value on the bus (without register).
     */
    public function writeRaw8($v) {
        $value = $v & 0xFF;
        wiringPiI2CWrite($this->address, $value);
    }

    /*
     * Write an 8-bit value to the specified register.
     */
    public function write8($register, $v) {
        //$value = $v & 0xFF;
        wiringPiI2CWriteReg8($this->address, $register, $v);
    }

    /**
     * Write a 16-bit value to the specified register.
     */
    public function write16($register, $v) {
        //$value = $v & 0xFF;
        wiringPiI2CWriteReg16($this->address, $register, $v);
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
        
        wiringPiI2CWriteBuffer($this->address, 1, 5, $pin, $mode, 0, 4);
    
        return 1;
    }
    
    # Arduino Digital Read
    public function digitalRead($cmd, $pin) {
        
        wiringPiI2CWriteBuffer ($this->address, 1, $cmd, $pin, 0, 0, 4);
        sleep(0.1);
        return wiringPiI2CReadReg8($this->address, 1);
        
    }
	
    # Arduino Digital Write
    public function digitalWrite($cmd, $value1, $value2, $value3) {
        
        wiringPiI2CWriteBuffer($this->address, 1, $cmd, $value1, $value2, $value3, 4);
    
	return 1;
    }
    
    # Arduino Digital Read
    public function analogRead($pin) {
        
        wiringPiI2CWriteBuffer ($this->address, 1, 3, $pin, 0, 0, 4);
        sleep(0.1);
        $number = wiringPiI2CReadBuffer ($this->address, 1, 0, 0, 4);
        $result = array_map ( function($val){return hexdec($val);} , explode(':', $number));
        
        return $result[2] * 256 + $result[3];
    }
	
    # Arduino Digital Write
    public function analogWrite($pin, $value2, $value3) {
        
        wiringPiI2CWriteBuffer($this->address, 1, 4, $pin, $value2, $value3, 4);
    
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
           $data[] =  wiringPiI2CRead($this->address) & 0xFF;
        }
        
        return $data;
    }
    
    /**
     * Read an 8-bit value on the bus (without register).
     */
    public function readRaw8() {
        return wiringPiI2CRead($this->address) & 0xFF;
    }

    /**
     * Read an unsigned byte from the specified register.
     */
    public function readU8($register) {
        return wiringPiI2CReadReg8($this->address, $register) & 0xFF;
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
        $result = wiringPiI2CReadReg16($this->address, $register);
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
     * @param type $register
     */
    public function readU16LE($register) {
        return $this->readU16($register, true);
    }

    /**
     * Read an unsigned 16-bit value from the specified register, in big endian byte order.
     *
     * @param type $register
     */
    public function readU16BE($register) {
        return $this->readU16($register, false);
    }

    /**
     * Read a signed 16-bit value from the specified register, in little endian byte order.
     *
     * @param type $register
     */
    public function readS16LE($register) {
        return $this->readS16($register, true);
    }

    /**
     * Read a signed 16-bit value from the specified register, in big endian byte order.
     *
     * @param hex $register
     */
    public function readS16BE($register) {
        return $this->readS16($register, false);
    }
}
