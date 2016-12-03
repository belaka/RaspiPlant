<?php

namespace FullVibes\Bundle\BoardBundle\Model;

class ActuatorModel
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
    protected $pin;
    
    /**
     * 
     * @param string $name
     * @param int $pin
     */
    public function __construct($name, $pin) {
        $this->name = $name;
        $this->pin = $pin;
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
