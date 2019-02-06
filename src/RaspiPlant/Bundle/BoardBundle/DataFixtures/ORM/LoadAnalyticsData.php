<?php

namespace RaspiPlant\Bundle\BoardBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RaspiPlant\Bundle\BoardBundle\Entity\Analytics;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAnalyticsData implements FixtureInterface, ContainerAwareInterface
{
    public function load(ObjectManager $manager)
    {

        //@TODO  apparently all fixtures should be done or none
        // we need sensors in order to have data
        $analytic = new Analytics();
        $analytic->setEventDate(new \DateTime());
        $analytic->setEventKey('key');
        $analytic->setEventValue(10.2);
        $analytic->setSensor($sensor);

        $manager->persist($analytic);
        $manager->flush();
    }

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}
