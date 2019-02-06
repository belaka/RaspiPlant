<?php

namespace RaspiPlant\Component\Sensor;

abstract class AbstractSensor implements SensorInterface {

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
