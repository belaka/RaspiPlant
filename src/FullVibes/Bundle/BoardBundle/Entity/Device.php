<?php

namespace FullVibes\Bundle\BoardBundle\Entity;

use FullVibes\Bundle\BoardBundle\Model\DeviceModel;

/*
 * Device Entity
 */

/**
 * Description of Device
 *
 * @author belaka
 */
class Device extends DeviceModel {

    /**
     *
     * @var int
     */
    protected $id;

    /**
     * 
     * @param int $id
     * @return \FullVibes\Bundle\BoardBundle\Entity\Device
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
