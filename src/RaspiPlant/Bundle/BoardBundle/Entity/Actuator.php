<?php

namespace RaspiPlant\Bundle\BoardBundle\Entity;

use RaspiPlant\Bundle\BoardBundle\Model\ActuatorModel;
use Cocur\Slugify\Slugify;

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
     * @return \RaspiPlant\Bundle\BoardBundle\Entity\Actuator
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
            $slugify = new Slugify(array('separator' => '_'));
            $this->setSlug($slugify->slugify($this->name));
        }
    }

    /**
     *
     */
    public function preUpdate()
    {
        if (!empty($this->name)) {
            $slugify = new Slugify(array('separator' => '_'));
            $this->setSlug($slugify->slugify($this->name));
        }
    }

}
