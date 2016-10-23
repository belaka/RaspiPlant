<?php

namespace FullVibes\Bundle\BoardBundle\Entity;

use FullVibes\Bundle\BoardBundle\Model\AnalyticsModel;

/*
 * Analytics Entity
 */

/**
 * Description of Analytics
 *
 * @author belaka
 */
class Analytics extends AnalyticsModel {

    /**
     *
     * @var int
     */
    protected $id;

    /**
     * 
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * 
     * @return int
     */
    public function getId() {
        return $this->id;
    }

}
