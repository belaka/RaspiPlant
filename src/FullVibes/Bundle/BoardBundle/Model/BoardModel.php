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
     * @var Collection
     */
    protected $devices;
    
    /**
     * 
     * @param string $name
     * @param int $address
     */
    public function __construct($name) {
        $this->name = $name;
    }
    
    public function getName() {
        return $this->name;
    }

    public function getSlug() {
        return $this->slug;
    }
    
    public function getDevices() {
        return $this->devices;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setSlug($slug) {
        $this->slug = $slug;
        return $this;
    }
    
    public function setDevices(Collection $devices) {
        $this->devices = $devices;
        return $this;
    }
    
}
