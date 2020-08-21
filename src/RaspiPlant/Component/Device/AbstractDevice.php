<?php

namespace RaspiPlant\Component\Device;

abstract class AbstractDevice implements DeviceInterface {

    /**
     *
     * @var string
     */
    public $name;

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }
}
