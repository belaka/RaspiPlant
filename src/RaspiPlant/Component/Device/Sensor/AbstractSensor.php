<?php

namespace RaspiPlant\Component\Device\Sensor;

abstract class AbstractSensor implements SensorInterface {

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
