<?php

namespace RaspiPlant\Bundle\DeviceBundle\Provider;

use RaspiPlant\Component\Device\DeviceInterface;

/**
 * @author Vincent Honnorat <vincent.honnorat@laposte.net>
 */
class DeviceProvider implements DeviceProviderInterface
{

    /** @var DeviceInterface[] */
    protected $devices;


    public function __construct()
    {
        $this->devices = [];
    }

    /**
     * @return DeviceInterface[]
     */
    public function all()
    {
        return $this->devices;
    }

    /**
     * @param string $id
     *
     * @return boolean|DeviceInterface
     */
    public function get($id)
    {
        if(!array_key_exists($id, $this->devices)) {
            return false;
        }

        return $this->devices[$id];
    }

    /**
     * @param string $id
     *
     * @return boolean
     */
    public function has($id)
    {
        return array_key_exists($id, $this->devices);
    }

    /**
     * @param DeviceInterface $device
     */
    public function registerDevice($device, $type)
    {
        $this->devices[$type][] = $device;
    }
}
