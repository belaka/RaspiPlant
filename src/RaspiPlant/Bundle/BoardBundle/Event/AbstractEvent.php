<?php

namespace RaspiPlant\Bundle\BoardBundle\Event;

use Symfony\Component\EventDispatcher\Event;

/**
 * Description of AbstractEvent
 *
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
abstract class AbstractEvent extends Event {

    /**
     *
     * @var I2cDevice
     */
    protected $device;

    /**
     *
     * @var string
     */
    protected $type;

    /**
     *
     * @var int
     */
    protected $value;

    /**
     *
     * @param I2CDevice $device
     * @param int    $value
     * @param string $type
     */
    public function __construct($device, $value, $type = 'digital') {

        $this->device = $device;
        $this->value = $value;
        $this->type = $type;
    }

    /**
     *
     * @return I2CDevice
     */
    public function getDevice() {
        return $this->device;
    }

    /**
     *
     * @return string
     */
    public function getType() {
        return $this->type;
    }

    /**
     *
     * @return int
     */
    public function getValue() {
        return $this->value;
    }

}
