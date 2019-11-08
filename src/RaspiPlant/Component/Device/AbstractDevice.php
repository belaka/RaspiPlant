<?php

namespace RaspiPlant\Component\Device;

abstract class AbstractDevice implements DeviceInterface {

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
