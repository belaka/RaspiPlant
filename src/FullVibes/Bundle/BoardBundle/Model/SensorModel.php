<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use FullVibes\Bundle\BoardBundle\Entity\Device;

class SensorModel
{
    /**
     *
     * @var string
     */
    protected $name;
    
    /**
     *
     * @var string
     */
    protected $slug;
    
    /**
     *
     * @var string
     */
    protected $class;
    
    /**
     *
     * @var int
     */
    protected $pin;
    
    /**
     *
     * @var \FullVibes\Bundle\BoardBundle\Entity\Device
     */
    protected $device;

    /**
     * 
     * @param string $name
     * @param int $pin
     */
    public function __construct($name, $class, $pin) {
        $this->name = $name;
        $this->class = $class;
        $this->pin = $pin;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getSlug() {
        return $this->slug;
    }
    
    public function getClass() {
        return $this->class;
    }

    public function getPin() {
        return $this->pin;
    }
    
    public function getDevice() {
        return $this->device;
    }

    public function setClass($class) {
        $this->class = $class;
        return $this;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    public function setPin($pin) {
        $this->pin = $pin;
        return $this;
    }

    public function setDevice(Device $device) {
        $this->device = $device;
        return $this;
    }
}
