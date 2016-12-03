<?php

namespace FullVibes\Bundle\BoardBundle\Model;

class BoardModel
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
     * @var int
     */
    protected $address;
    
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
     * @var Collection
     */
    protected $errors;
    
    /**
     * 
     * @param string $name
     * @param int $address
     */
    public function __construct($name, $address) {
        $this->name = $name;
        $this->address = $address;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getSlug() {
        return $this->slug;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getSensors() {
        return $this->sensors;
    }

    public function getActuators() {
        return $this->actuators;
    }

    public function getErrors() {
        return $this->errors;
    }
    
    
    
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }

    public function setAddress($address) {
        $this->address = $address;
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

    public function setErrors(Collection $errors) {
        $this->errors = $errors;
        return $this;
    }

    
}
