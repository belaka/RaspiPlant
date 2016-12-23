<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use FullVibes\Bundle\BoardBundle\Entity\Device;

class ActuatorModel implements ActivableInterface
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
     * @var class
     */
    protected $class;
    
    /**
     *
     * @var int
     */
    protected $pin;
    
    /**
     *
     * @var int
     */
    protected $state;
    
    /**
     *
     * @var \FullVibes\Bundle\BoardBundle\Entity\Device
     */
    protected $device;

    /**
     * @var boolean
     */
    protected $active;

    /**
     * ActuatorModel constructor.
     * @param $name
     * @param $class
     * @param $pin
     */
    public function __construct($name, $class, $pin) {
        $this->name = $name;
        $this->class = $class;
        $this->pin = $pin;
        $this->active = false;
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return class
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @return int
     */
    public function getPin() {
        return $this->pin;
    }

    /**
     * @return Device
     */
    public function getDevice() {
        return $this->device;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $slug
     * @return $this
     */
    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param $pin
     * @return $this
     */
    public function setPin($pin) {
        $this->pin = $pin;
        return $this;
    }

    /**
     * @param $class
     * @return $this
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }

    /**
     * @param $state
     * @return $this
     */
    public function setState($state) {
        $this->state = $state;
        return $this;
    }

    /**
     * @param Device $device
     * @return $this
     */
    public function setDevice(Device $device) {
        $this->device = $device;
        return $this;
    }

    /**
     * @param bool $active
     * @return ActuatorModel
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

}
