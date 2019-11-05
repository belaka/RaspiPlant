<?php

namespace RaspiPlant\Component\Device\Display;

use RaspiPlant\Bundle\DeviceBundle\Protocol\I2CProtocol;
use RaspiPlant\Bundle\DeviceBundle\Protocol\ProtocolInterface;

class LcdRgbBacklightDisplay extends AbstractDisplay {

    //this device has two I2C addresses

    /**
     * RGB I2C addresses
     */
    const DISPLAY_RGB_ADDR = 0x62;

    /**
     * TEXT I2C addresses
     */
    const DISPLAY_TEXT_ADDR = 0x3e;


    /**
     *
     * @param int $pin
     * @param boolean $debug
     */
    public function __construct($debug = false) {

        $this->debug = $debug;

        $this->display = wiringpii2csetup(self::DISPLAY_TEXT_ADDR);
        $this->displayDevice = new I2CProtocol($this->display);

        $this->backlight = wiringpii2csetup(self::DISPLAY_RGB_ADDR);
        $this->backlightDevice = new I2CProtocol($this->backlight);
    }

    /**
     *
     */
    public function __destruct() {
        $this->setRGB(0, 0, 0);
        $this->textCommand(0x01);
    }


    /**
     * # set backlight to (R,G,B) (values from 0..255 for each)
     * @param int $r
     * @param int $g
     * @param int $b
     * @return int
     */
    public function setRGB($r, $g, $b) {

        $this->backlightDevice->write8(0,0);
        $this->backlightDevice->write8(1,0);
        $this->backlightDevice->write8(0x08,0xaa);
        $this->backlightDevice->write8(4,$r);
        $this->backlightDevice->write8(3,$g);
        $this->backlightDevice->write8(2,$b);

        return 1;
    }

    protected function textCommand($cmd) {
        // # send command to display (no need for external use)
        $this->displayDevice->write8(0x80,$cmd);

        return 1;
    }

    /**
     * set display text \n for second line(or auto wrap)
     *
     * @param type $text
     */
    public function setText($text, $refresh = true) {

        if ($refresh) {
            // clear display
            $this->textCommand(0x01);
        } else {
            // return home
            $this->textCommand(0x02);
        }

        usleep(2000);

        //  display on, no cursor
        $this->textCommand(0x08 | 0x04);

        // 2 lines
        $this->textCommand(0x28);

        usleep(2000);

        $count = 0;
        $row = 0;
        for ($i = 0; $i < strlen($text); $i++) {

            if ($text[$i] == "\n" or $count == 16) {
                $count = 0;
                $row += 1;

                if ($row == 2) {
                   break;
                }

                $this->textCommand(0xc0);

                if ($text[$i] == "\n") {
                   continue;
                }

            }
            $count += 1;
            $this->displayDevice->write8(0x40, ord($text[$i]));
        }

        return 1;
    }

    /**
     * Test
     */
    public function testDisplay() {

        $this->setText("ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789");

        //Set screen to green
        $this->setRGB(0,255,0);
        $this->setText("Hello world\nThis is an LCD test\n");

        sleep(1);

        $this->setRGB(0,128,64);

        for ($c = 0; $c < 256; $c++) {
            $this->setRGB($c,255-$c,0);
            usleep(20000);
        }

        $this->setText("Bye bye, this should wrap onto next line\n");

        $this->setRGB(0,128,64);

        $buf = str_split("Grove -Update without erase");
        $this->setText(implode('', $buf));
        sleep(1);

        for ($i = 0; $i < count($buf); $i++) {
            $buf[$i]=".";
            $this->setText(implode('', $buf), false);
            usleep(10000);
        }
         die();
    }
}
