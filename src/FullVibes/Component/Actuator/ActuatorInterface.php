<?php

namespace FullVibes\Component\Actuator;

/**
 * Interface Class for actuators.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
interface ActuatorInterface {
    
    public function writeStatus($status);

    public function readStatus();
    
    public function getName();
    
    public function setName($name);

}
