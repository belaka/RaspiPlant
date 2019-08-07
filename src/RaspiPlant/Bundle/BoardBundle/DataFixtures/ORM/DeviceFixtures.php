<?php

namespace RaspiPlant\Bundle\BoardBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RaspiPlant\Bundle\BoardBundle\Entity\Device;
use RaspiPlant\omponent\Device\I2CDevice;

class DeviceFixtures extends Fixture implements DependentFixtureInterface
{
    public const DEVICE_REFERENCE = 'TestDevice';

    public function load(ObjectManager $manager)
    {
        $device = new Device();
        $device->setName(self::DEVICE_REFERENCE);
        $device->setClass(I2CDevice::class);
        $device->setActive(true);
        $device->setBoard($this->getReference(BoardFixtures::BOARD_REFERENCE));

        $manager->persist($device);

        $manager->flush();

        $this->addReference(self::DEVICE_REFERENCE, $device);
    }

    public function getDependencies()
    {
        return array(
            BoardFixtures::class
        );
    }
}
