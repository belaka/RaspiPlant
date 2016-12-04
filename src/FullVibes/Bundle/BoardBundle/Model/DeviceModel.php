<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use FullVibes\Bundle\BoardBundle\Entity\Board;
use Doctrine\Common\Collections\Collection;

class DeviceModel
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
     * 
     * @param string $name
     * @param int $address
     * @param int $class
     */
    public function __construct($name, $address, $class = "FullVibes\\Component\\Device\\I2CDevice") {
        $this->name = $name;
        $this->class = $class;
        $this->address = $address;
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

    public function getAddress() {
        return $this->address;
    }
    
    public function getBoard() {
        return $this->board;
    }

    public function getSensors() {
        return $this->sensors;
    }

    public function getActuators() {
        return $this->actuators;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }
    
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this;
    }
    
    public function setBoard(Board $board) {
        $this->board = $board;
        return $this;
    }

    public function setSensors(Collection $sensors) {
        $this->sensors = $sensors;
        return $this;
    }

    public function setActuators(Collection $actuators) {
        $this->actuators = $actuators;
        return $this;
    }

}
