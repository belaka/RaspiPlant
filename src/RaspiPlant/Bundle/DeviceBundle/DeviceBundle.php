<?php

namespace RaspiPlant\Bundle\DeviceBundle;

use RaspiPlant\Bundle\DeviceBundle\DependencyInjection\Compiler\CheckForDevices;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DeviceBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
