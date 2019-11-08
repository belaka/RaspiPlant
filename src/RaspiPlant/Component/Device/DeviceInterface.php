<?php

namespace RaspiPlant\Component\Device;

/**
 * Interface Class for devices.
 *
 * @author Vincent Honnorat <full-vibes@gmail.com>
 */
interface DeviceInterface
{
    public function getName();

    public function setName($name);
}
