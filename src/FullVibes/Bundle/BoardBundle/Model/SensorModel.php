<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use FullVibes\Component\Model\AbstractModel;
use FullVibes\Bundle\BoardBundle\Entity\Device;

class SensorModel extends AbstractModel implements ActivableInterface
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
    protected $pin = null;
    
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
     * SensorModel constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->active = false;
        parent::__construct($data);
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
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @return int|null
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
     * @param $class
     * @return $this
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
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
     * @param Device $device
     * @return $this
     */
    public function setDevice(Device $device) {
        $this->device = $device;
        return $this;
    }

    /**
     * @param bool $active
     * @return SensorModel
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

}
