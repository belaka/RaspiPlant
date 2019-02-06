<?php

namespace RaspiPlant\Component\Actuator;

use RaspiPlant\Component\Device\I2CDevice;

class ChainableRGBLedActuator extends AbstractActuator {

    /**
     *
     * @var I2CDevice
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
     * @var boolean
     */
    protected $debug;

    ////////////////////////////////////////////////////////////////////////////
    // Grove Chainable RGB LED commands
    // Store color for later use
    const STORE_COLOR_CMD = 90;
    // Initialise
    const CHAINABLERGB_INIT_CMD = 91;
    // Initialise and test with a simple color
    const CHAINABLERGB_TEST_CMD = 92;
    // Set one or more leds to the stored color by pattern
    const CHAINABLERGB_PATTERN_CMD = 93;
    // set one or more leds to the stored color by modulo
    const CHAINABLERGB_MODULO_CMD = 94;
    // sets leds similar to a bar graph, reversible
    const CHAINABLERGB_LEVEL_CMD = 95;

    /**
     *
     * @param int $pin
     * @param boolean $debug
     */
    public function __construct(I2CDevice $device, $pin, $name, $debug = false) {
        $this->debug = $debug;
        $this->pin = $pin;
        $this->name = $name;
        $this->device = $device;
        $this->device->pinMode($this->pin, "OUTPUT");
    }

    /**
     *
     * @return int
     */
    public function readStatus() {
        throw new \Exception('NOT IMPLEMENTED');
    }

    public function writeStatus($status) {
        throw new \Exception('NOT IMPLEMENTED');
    }

    /**
     * Grove Chainable RGB LED - store a color for later use
     * red: 0-255
     * green: 0-255
     * blue: 0-255
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     */
    public function storeColor($red, $green, $blue) {
        # switch relay on
        $this->device->digitalWrite(self::STORE_COLOR_CMD, $red, $green, $blue);
        sleep(parent::ACTUATOR_WAIT_TIME);

        return 1;
    }

    /**
     * Grove Chainable RGB LED - initialise
     * numLeds: how many leds do you have in the chain
     *
     * @param int $numLeds
     */
    public function initChain($numLeds) {
        $this->device->digitalWrite(self::CHAINABLERGB_INIT_CMD, $this->pin, $numLeds, 0);
        sleep(parent::ACTUATOR_WAIT_TIME);

        return 1;
    }

    /**
     * Grove Chainable RGB LED - initialise and test with a simple color
     * numLeds: how many leds do you have in the chain
     * testColor: (0-7) 3 bits in total - a bit for red, green and blue, eg. 0x04 == 0b100 (0bRGB) == rgb(255, 0, 0) == #FF0000 == red
     *            ie. 0 black, 1 blue, 2 green, 3 cyan, 4 red, 5 magenta, 6 yellow, 7 white
     *
     * @param type $numLeds
     * @param type $testColor
     */
    public function testChain($numLeds, $testColor) {
        $this->device->digitalWrite(self::CHAINABLERGB_TEST_CMD, $this->pin, $numLeds, $testColor);
        sleep(parent::ACTUATOR_WAIT_TIME);

        return 1;
    }

    /**
     *  Grove Chainable RGB LED - set one or more leds to the stored color by pattern
     *  pattern: (0-3) 0 = this led only, 1 all leds except this led, 2 this led and all leds inwards, 3 this led and all leds outwards
     *  whichLed: index of led you wish to set counting outwards from the GrovePi, 0 = led closest to the GrovePi
     *
     * @param type $pattern
     * @param type $led
     */
    public function patternChain($pattern, $led) {
        $this->device->digitalWrite(self::CHAINABLERGB_PATTERN_CMD, $this->pin, $pattern, $led);
        sleep(parent::ACTUATOR_WAIT_TIME);

        return 1;
    }

    /**
     * Grove Chainable RGB LED - set one or more leds to the stored color by modulo
     * offset: index of led you wish to start at, 0 = led closest to the GrovePi, counting outwards
     * divisor: when 1 (default) sets stored color on all leds >= offset, when 2 sets every 2nd led >= offset and so on
     *
     * @param type $offset
     * @param type $divisor
     */
    public function moduloChain($offset, $divisor) {
        $this->device->digitalWrite(self::CHAINABLERGB_MODULO_CMD, $this->pin, $offset, $divisor);
        sleep(parent::ACTUATOR_WAIT_TIME);

        return 1;
    }

    /**
     * Grove Chainable RGB LED - sets leds similar to a bar graph, reversible
     * level: (0-10) the number of leds you wish to set to the stored color
     * reversible (0-1) when 0 counting outwards from GrovePi, 0 = led closest to the GrovePi, otherwise counting inwards
     *
     * @param type $level
     * @param type $reverse
     */
    public function levelChain($level, $reverse) {
        $this->device->digitalWrite(self::CHAINABLERGB_LEVEL_CMD, $this->pin, $level, $reverse);
        sleep(parent::ACTUATOR_WAIT_TIME);

        return 1;
    }

    /***************************************************************************

    # Grove Chainable RGB LED - store a color for later use
    # red: 0-255
    # green: 0-255
    # blue: 0-255
    def storeColor(red, green, blue):
            write_i2c_block(address, storeColor_cmd + [red, green, blue])
            time.sleep(.05)
            return 1

    # Grove Chainable RGB LED - initialise
    # numLeds: how many leds do you have in the chain
    def chainableRgbLed_init(pin, numLeds):
            write_i2c_block(address, chainableRgbLedInit_cmd + [pin, numLeds, unused])
            time.sleep(.05)
            return 1

    # Grove Chainable RGB LED - initialise and test with a simple color
    # numLeds: how many leds do you have in the chain
    # testColor: (0-7) 3 bits in total - a bit for red, green and blue, eg. 0x04 == 0b100 (0bRGB) == rgb(255, 0, 0) == #FF0000 == red
    #            ie. 0 black, 1 blue, 2 green, 3 cyan, 4 red, 5 magenta, 6 yellow, 7 white
    def chainableRgbLed_test(pin, numLeds, testColor):
            write_i2c_block(address, chainableRgbLedTest_cmd + [pin, numLeds, testColor])
            time.sleep(.05)
            return 1

    # Grove Chainable RGB LED - set one or more leds to the stored color by pattern
    # pattern: (0-3) 0 = this led only, 1 all leds except this led, 2 this led and all leds inwards, 3 this led and all leds outwards
    # whichLed: index of led you wish to set counting outwards from the GrovePi, 0 = led closest to the GrovePi
    def chainableRgbLed_pattern(pin, pattern, whichLed):
            write_i2c_block(address, chainableRgbLedSetPattern_cmd + [pin, pattern, whichLed])
            time.sleep(.05)
            return 1

    # Grove Chainable RGB LED - set one or more leds to the stored color by modulo
    # offset: index of led you wish to start at, 0 = led closest to the GrovePi, counting outwards
    # divisor: when 1 (default) sets stored color on all leds >= offset, when 2 sets every 2nd led >= offset and so on
    def chainableRgbLed_modulo(pin, offset, divisor):
            write_i2c_block(address, chainableRgbLedSetModulo_cmd + [pin, offset, divisor])
            time.sleep(.05)
            return 1

    # Grove Chainable RGB LED - sets leds similar to a bar graph, reversible
    # level: (0-10) the number of leds you wish to set to the stored color
    # reversible (0-1) when 0 counting outwards from GrovePi, 0 = led closest to the GrovePi, otherwise counting inwards
    def chainableRgbLed_setLevel(pin, level, reverse):
            write_i2c_block(address, chainableRgbLedSetLevel_cmd + [pin, level, reverse])
            time.sleep(.05)
            return 1

    ****************************************************************************/

    public static function getControls() {
        return array(
            'state' => array(
                'on' => 1,
                'off' => 0
            )
        );
    }

}
