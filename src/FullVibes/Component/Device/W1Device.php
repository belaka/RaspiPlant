<?php

namespace FullVibes\Component\Device;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Class for communicating with a W1 device using the console command.
 *
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class W1Device extends AbstractDevice
{

    protected $address;

    /**
     * I2CDevice constructor.
     * Create an instance of the I2C device at the specified address on the
     * specified I2C bus number.
     *
     * @param $address
     */
    public function __construct($address) {
        $this->address = $address;
    }

    /**
     * @param $deviceId
     * @return string
     */
    public function readAddress($deviceId) {
        //cat /sys/devices/w1_bus_master1/28-0000073b00e1/w1_slave
        $process = new Process('cat ' . $this->address . $deviceId . '/w1_slave');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        return $process->getOutput();
    }
}
