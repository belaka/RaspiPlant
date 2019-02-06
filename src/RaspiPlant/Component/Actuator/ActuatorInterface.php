<?php

namespace RaspiPlant\Component\Actuator;

/**
 * Interface Class for actuators.
 *
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
interface ActuatorInterface {

    public function writeStatus($status);

    public function readStatus();

    public function getName();

    public function setName($name);

    public static function getControls();

}
