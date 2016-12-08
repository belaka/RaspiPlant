<?php

namespace FullVibes\Component\Actuator;

/**
 * Abstract Class for communicating with an actuator.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
abstract class AbstractActuator implements ActuatorInterface {
    
    const DIGITAL_WRITE_COMMAND = 2; // Digital Write Command byte
    
    const DIGITAL_READ_COMMAND = 1; // Digital Write Command byte


    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

}
