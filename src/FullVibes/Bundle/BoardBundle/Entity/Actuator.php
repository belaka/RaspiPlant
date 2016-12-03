<?php

namespace FullVibes\Bundle\BoardBundle\Entity;

use FullVibes\Bundle\BoardBundle\Model\ActuatorModel;

/*
 * Actuator Entity
 */

/**
 * Description of Analytics
 *
 * @author belaka
 */
class Actuator extends ActuatorModel {

    /**
     *
     * @var int
     */
    protected $id;

    /**
     * 
     * @param int $id
     * @return \FullVibes\Bundle\BoardBundle\Entity\Actuator
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

}
