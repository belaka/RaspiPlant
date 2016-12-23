<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use FullVibes\Bundle\BoardBundle\Entity\Board;
use Doctrine\Common\Collections\Collection;

class DeviceModel implements ActivableInterface
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
    protected $class;
    
    /**
     *
     * @var string
     */
    protected $slug;
    
    /**
     *
     * @var int
     */
    protected $address;
    
    /**
     *
     * @var \FullVibes\Bundle\BoardBundle\Entity\Board
     */
    protected $board;
    
    /**
     *
     * @var Collection
     */
    protected $sensors;
    
    /**
     *
     * @var Collection
     */
    protected $actuators;

    /**
     * @var boolean
     */
    protected $active;

    /**
     * DeviceModel constructor.
     * @param $name
     * @param $address
     * @param string $class
     */
    public function __construct($name, $address, $class = "FullVibes\\Component\\Device\\I2CDevice") {
        $this->name = $name;
        $this->class = $class;
        $this->address = $address;
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
     * @return int
     */
    public function getAddress() {
        return $this->address;
    }

    /**
     * @return Board
     */
    public function getBoard() {
        return $this->board;
    }

    /**
     * @return Collection
     */
    public function getSensors() {
        return $this->sensors;
    }

    /**
     * @return Collection
     */
    public function getActuators() {
        return $this->actuators;
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
     * @param $class
     * @return $this
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }

    /**
     * @param $address
     * @return $this
     */
    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }

    /**
     * @param Board $board
     * @return $this
     */
    public function setBoard(Board $board) {
        $this->board = $board;
        return $this;
    }

    /**
     * @param Collection $sensors
     * @return $this
     */
    public function setSensors(Collection $sensors) {
        $this->sensors = $sensors;
        return $this;
    }

    /**
     * @param Collection $actuators
     * @return $this
     */
    public function setActuators(Collection $actuators) {
        $this->actuators = $actuators;
        return $this;
    }


    /**
     * @param bool $active
     * @return DeviceModel
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

}
