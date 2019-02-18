<?php

namespace RaspiPlant\Bundle\BoardBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use RaspiPlant\Bundle\BoardBundle\Entity\Actuator;

class ActuatorFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $actuator = new Actuator();
        $actuator->setName('TestActuator');

        $manager->persist($actuator);

        $manager->flush();
    }
}
