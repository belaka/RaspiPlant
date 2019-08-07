<?php

namespace RaspiPlant\Bundle\BoardBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RaspiPlant\Bundle\BoardBundle\Entity\Analytics;

class AnalyticsFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $sensor = $this->getReference(SensorFixtures::SENSOR_REFERENCE);
        //@TODO  apparently all fixtures should be done or none
        // we need sensors in order to have data
        $analytic = new Analytics();
        $analytic->setEventDate(new \DateTime());
        $analytic->setEventKey($sensor->getId() . '_air_quality');
        $analytic->setEventValue(10.2);
        $analytic->setSensor($sensor);

        $manager->persist($analytic);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            SensorFixtures::class
        );
    }
}
