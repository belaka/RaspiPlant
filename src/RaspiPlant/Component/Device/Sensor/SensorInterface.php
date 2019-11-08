<?php

namespace RaspiPlant\Component\Device\Sensor;

use RaspiPlant\Component\Device\DeviceInterface;

/**
 * Interface Class for sensors.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
interface SensorInterface extends DeviceInterface
{

    public static function getFields();

    public function readSensorData();
}
