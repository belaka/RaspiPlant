<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use Doctrine\Common\Collections\Collection;

class BoardModel implements ActivableInterface
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
     * @var boolean
     */
    protected $active;

    /**
     * BoardModel constructor.
     * @param $name
     */
    public function __construct($name) {
        $this->name = $name;
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
     * @return Collection
     */
    public function getDevices() {
        return $this->devices;
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
     * @param Collection $devices
     * @return $this
     */
    public function setDevices(Collection $devices) {
        $this->devices = $devices;
        return $this;
    }

    /**
     * @param bool $active
     * @return BoardModel
     */
    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }


}
