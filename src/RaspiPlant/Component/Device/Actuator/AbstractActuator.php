<?php

namespace RaspiPlant\Component\Device\Actuator;

/**
 * Abstract Class for communicating with an actuator.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
abstract class AbstractActuator implements ActuatorInterface
{

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

}
