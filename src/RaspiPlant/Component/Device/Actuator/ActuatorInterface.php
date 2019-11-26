<?php

namespace RaspiPlant\Component\Device\Actuator;

use RaspiPlant\Component\Device\DeviceInterface;

/**
 * Interface Class for actuators.
 *
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
interface ActuatorInterface extends DeviceInterface
{

    public static function getControls();

    public function writeStatus($status);

    public function readStatus();

}
