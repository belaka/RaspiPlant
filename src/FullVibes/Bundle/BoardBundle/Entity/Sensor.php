<?php

namespace FullVibes\Bundle\BoardBundle\Entity;

use FullVibes\Bundle\BoardBundle\Model\SensorModel;

/*
 * Sensor Entity
 */

/**
 * Description of Sensor
 *
 * @author belaka
 */
class Sensor extends SensorModel {

    /**
     *
     * @var int
     */
    protected $id;

    /**
     * 
     * @param int $id
     * @return \FullVibes\Bundle\BoardBundle\Entity\Sensor
     */
    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     *
     */
    public function prePersist()
    {
        if (!empty($this->name)) {
            $this->slug = $this->makeSlug($this->name);
        }
    }

    /**
     *
     */
    public function preUpdate()
    {
        if (!empty($this->name)) {
            $this->slug = $this->makeSlug($this->name);
        }
    }
}
