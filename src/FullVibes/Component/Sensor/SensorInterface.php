<?php

namespace FullVibes\Component\Sensor;

/**
 * Interface Class for sensors.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
interface SensorInterface {
    
    public function readSensorData();
    
    public function getFields();
}
