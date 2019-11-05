<?php

namespace RaspiPlant\Bundle\DeviceBundle\Provider;

use RaspiPlant\Component\Device\DeviceInterface;

/**
 * @author Vincent Honnorat <vincent.honnorat@laposte.net>
 */
interface DeviceProviderInterface
{
    /**
     * @param $device
     * @param $type
     * @return mixed
     */
    public function registerDevice($device, $type);
}
