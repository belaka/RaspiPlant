<?php

namespace RaspiPlant\Bundle\BoardBundle\Entity;

use RaspiPlant\Bundle\BoardBundle\Model\SensorValueModel;

/*
 * SensorValue Entity
 */

/**
 * Description of SensorValue
 *
 * @author belaka
 */
class SensorValue extends SensorValueModel
{

    /**
     *
     * @var int
     */
    protected $id;

    /**
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     *
     * @param int $id
     * @return \RaspiPlant\Bundle\BoardBundle\Entity\SensorValue
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

}
