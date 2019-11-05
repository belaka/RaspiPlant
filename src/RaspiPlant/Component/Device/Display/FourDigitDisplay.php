<?php

namespace RaspiPlant\Component\Device\Display;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

/**
 * Grove 4 Digit Display
 */
class FourDigitDisplay extends AbstractDisplay
{
    /**
     * I2C Address of GrovePi
     */
    const RPI_I2C_ADDRESS = 0x04;

    /**
     * Digital Write Command byte
     */
    const DIGITAL_WRITE_COMMAND = 2;

    /**
     * Digital Read Command byte
     */
    const DIGITAL_READ_COMMAND = 1;

    /**
     * Default wait time for display operation
     */
    const DISPLAY_WAIT_TIME = 1;

    /**
     * Initialise
     */
    const FOURDIGIT_INIT_CMD = 70;

    /**
     * Set brightness, not visible until next cmd
     */
    const FOURDIGIT_BRIGHTNESS_CMD = 71;

    /**
     * Set numeric value without leading zeros
     */
    const FOURDIGIT_VALUE_CMD = 72;

    /**
     * Set numeric value with leading zeros
     */
    const FOURDIGIT_ZEROS_VALUE_CMD = 73;

    /**
     * Set individual digit
     */
    const FOURDIGIT_INDIVIDUAL_DIGIT_CMD = 74;

    /**
     * Set individual leds of a segment
     */
    const FOURDIGIT_INDIVIDUAL_LED_CMD = 75;

    /**
     * Set left and right values with colon
     */
    const FOURDIGIT_SCORE_CMD = 76;

    /**
     * Analog read for n seconds
     */
    const FOURDIGIT_ANALOG_READ_CMD = 77;

    /**
     * Entire display on
     */
    const FOURDIGIT_ALL_ON_CMD = 78;

    /**
     * Entire display off
     */
    const FOURDIGIT_ALL_OFF_CMD = 79;

    /**
     *
     * @var ProtocolInterface
     */
    protected $protocol;

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

    /**
     *
     * @param int $pin
     * @param boolean $debug
     */
    public function __construct($pin, $debug = false)
    {

        $this->debug = $debug;

        $this->pin = $pin;

        $this->fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $this->protocol = new I2CProtocol($this->fd);

        $this->protocol->pinMode($this->pin, "OUTPUT");
    }

    public function __destruct()
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_ALL_OFF_CMD, $this->pin, 0, 0);
    }

    public function testDisplay()
    {

        $this->fourDigitOff();

        print ("Test 1) Initialise\n");
        $this->fourDigitInit();
        sleep(1);

        print ("Test 2) Set brightness\n");
        for ($i = 0; $i < 8; $i++) {
            print ("Test 2) Set brightness " . $i . "\n");
            $this->fourDigitBrightness($i);
            $this->fourDigitOn();
            sleep(1);
        }
        sleep(1);

        //set to lowest brightness level
        $this->fourDigitBrightness(0);
        sleep(1);

        print ("Test 3) Set number without leading zeros\n");
        $leadingZero = 0;
        $this->fourDigitNumber(1, $leadingZero);
        sleep(1);
        $this->fourDigitNumber(12, $leadingZero);
        sleep(1);
        $this->fourDigitNumber(123, $leadingZero);
        sleep(1);
        $this->fourDigitNumber(1234, $leadingZero);
        sleep(1);

        print ("Test 4) Set number with leading zeros\n");
        $leadingZero = 1;
        $this->fourDigitNumber(5, $leadingZero);
        sleep(1);
        $this->fourDigitNumber(56, $leadingZero);
        sleep(1);
        $this->fourDigitNumber(567, $leadingZero);
        sleep(1);
        $this->fourDigitNumber(5678, $leadingZero);
        sleep(1);

        print ("Test 5) Set individual digit\n");
        $this->fourDigitDigit(0, 2);
        $this->fourDigitDigit(1, 6);
        $this->fourDigitDigit(2, 9);
        $this->fourDigitDigit(3, 15); //15 = F
        sleep(1);

        print ("Test 6) Set individual segment\n");
        $this->fourDigitSegment(0, 118); //118 = H
        $this->fourDigitSegment(1, 121); //121 = E
        $this->fourDigitSegment(2, 118); //118 = H
        $this->fourDigitSegment(3, 121); //121 = E
        sleep(1);

        $this->fourDigitSegment(0, 57); //57 = C
        $this->fourDigitSegment(1, 63); //63 = O
        $this->fourDigitSegment(1, 63); //63 = O
        $this->fourDigitSegment(1, 56); //56 = L
        sleep(1);

        print ("Test 7) Set score\n");
        $this->fourDigitScore(0, 0);
        sleep(1);
        $this->fourDigitScore(1, 0);
        sleep(1);
        $this->fourDigitScore(1, 1);
        sleep(1);
        $this->fourDigitScore(1, 2);
        sleep(1);
        $this->fourDigitScore(1, 3);
        sleep(1);
        $this->fourDigitScore(1, 4);
        sleep(1);
        $this->fourDigitScore(1, 5);
        sleep(1);

        print ("Test 8) Set time\n");
        $time = explode(':', date('H:i'));
        $this->fourDigitScore($time[0], $time[1]);
        sleep(1);

        print ("Test 9) Monitor analog pin\n");
        $seconds = 10;
        $this->fourDigitMonitor(0, $seconds);
        sleep(1);

        print ("Test 10) Switch all on\n");
        $this->fourDigitOn();
        sleep(1);

        print ("Test 11) Switch all off\n");
        $this->fourDigitOff();
        sleep(1);
    }

    /**
     * Grove 4 Digit Display - turn entire display off
     */
    public function fourDigitOff()
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_ALL_OFF_CMD, $this->pin, 0, 0);
        sleep(0.05);

        return 1;
    }

    /**
     * Grove 4 Digit Display - initialise
     *
     * @return int
     */
    public function fourDigitInit()
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_INIT_CMD, $this->pin, 0, 0);

        return 1;
    }

    /**
     * Grove 4 Digit Display - set brightness
     * brightness: (0-7)
     *
     * @param int $brightness
     */
    public function fourDigitBrightness($brightness)
    {
        // not actually visible until next command is executed
        $this->protocol->digitalWrite(self::FOURDIGIT_BRIGHTNESS_CMD, $this->pin, $brightness, 0);
        sleep(0.05);

        return 1;
    }

    /**
     * Grove 4 Digit Display - turn entire display on (88:88)
     */
    public function fourDigitOn()
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_ALL_ON_CMD, $this->pin, 0, 0);
        sleep(0.05);

        return 1;
    }

    /**
     * Grove 4 Digit Display - set numeric value with or without leading zeros
     * value: (0-65535) or (0000-FFFF)
     *
     * @param int $value
     * @param boolean $leadingZero
     */
    public function fourDigitNumber($value, $leadingZero)
    {

        //split the value into two bytes so we can render 0000-FFFF on the display
        $byte1 = $value & 255;
        $byte2 = $value >> 8;

        //separate commands to overcome current 4 bytes per command limitation
        if ($leadingZero) {
            $this->protocol->digitalWrite(self::FOURDIGIT_VALUE_CMD, $this->pin, $byte1, $byte2);
        } else {
            $this->protocol->digitalWrite(self::FOURDIGIT_ZEROS_VALUE_CMD, $this->pin, $byte1, $byte2);
        }

        sleep(0.05);

        return 1;
    }

    /**
     * Grove 4 Digit Display - set individual segment (0-9,A-F)
     * segment: (0-3)
     * value: (0-15) or (0-F)
     *
     * @param int $segment
     * @param int $value
     */
    public function fourDigitDigit($segment, $value)
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_INDIVIDUAL_DIGIT_CMD, $this->pin, $segment, $value);
        sleep(0.05);

        return 1;
    }

    /**
     * Grove 4 Digit Display - set 7 individual leds of a segment
     *  segment: (0-3)
     *  leds: (0-255) or (0-0xFF) one bit per led, segment 2 is special, 8th bit is the colon
     *
     * @param int $segment
     * @param int $leds
     */
    public function fourDigitSegment($segment, $leds)
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_INDIVIDUAL_LED_CMD, $this->pin, $segment, $leds);
        sleep(0.05);

        return 1;
    }

    /**
     * Grove 4 Digit Display - set left and right values (0-99), with leading zeros and a colon
     *   left: (0-255) or (0-FF)
     *   right: (0-255) or (0-FF)
     *   colon will be lit
     *
     * @param int $left
     * @param int $right
     */
    public function fourDigitScore($left, $right)
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_SCORE_CMD, $this->pin, $left, $right);
        sleep(0.05);

        return 1;
    }

    /**
     * Grove 4 Digit Display - display analogRead value for n seconds, 4 samples per second
     *   analog: analog pin to read
     *   duration: analog read for this many seconds
     *
     * @param int $analog
     * @param int $duration
     */
    public function fourDigitMonitor($analog, $duration)
    {
        $this->protocol->digitalWrite(self::FOURDIGIT_ANALOG_READ_CMD, $this->pin, $analog, $duration);
        sleep($duration + 0.05);

        return 1;
    }

}
