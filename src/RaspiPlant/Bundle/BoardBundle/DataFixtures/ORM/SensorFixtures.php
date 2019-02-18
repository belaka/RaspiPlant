<?php

namespace RaspiPlant\Bundle\BoardBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RaspiPlant\Bundle\BoardBundle\Entity\Sensor;
use RaspiPlant\Component\Sensor\AirQualitySensor;

class SensorFixtures extends Fixture implements DependentFixtureInterface
{
    public const SENSOR_REFERENCE = 'TestSensor';

    public function load(ObjectManager $manager)
    {
        $sensor = new Sensor();
        $sensor->setName(self::SENSOR_REFERENCE);
        $sensor->setClass(AirQualitySensor::class);
        $sensor->setActive(true);
        $sensor->setDevice($this->getReference(DeviceFixtures::DEVICE_REFERENCE));

        $manager->persist($sensor);

        $manager->flush();

        $this->addReference(self::SENSOR_REFERENCE, $sensor);
    }

    public function getDependencies()
    {
        return array(
            DeviceFixtures::class
        );
    }
}
