<?php

namespace RaspiPlant\Bundle\BoardBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use RaspiPlant\Bundle\BoardBundle\Entity\Analytics;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class AnalyticsManager
{
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;

    /**
     *
     * @var \Doctrine\ORM\Mapping\ClassMetadata
     */
    protected $metadata;

    /**
     *
     * @var string
     */
    protected $classNamespace;

    /**
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = 'RaspiPlant\Bundle\BoardBundle\Entity\Analytics';
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    /**
     * @param \RaspiPlant\Bundle\BoardBundle\Entity\analytics $analytics
     */
    public function create(Analytics $analytics)
    {
        $this->em->persist($analytics);
        $this->em->flush($analytics);
    }

    /**
     * @param \RaspiPlant\Bundle\BoardBundle\Entity\analytics $analytics
     */
    public function save(Analytics $analytics)
    {
        $this->em->persist($analytics);
        $this->em->flush($analytics);
    }

    /**
     * @param \RaspiPlant\Bundle\BoardBundle\Entity\analytics $analytics
     */
    public function update(Analytics $analytics)
    {
        $this->em->flush($analytics);
    }

    /**
     * @param \RaspiPlant\Bundle\BoardBundle\Entity\analytics $analytics
     */
    public function remove(Analytics $analytics)
    {
        $this->em->remove($analytics);
        $this->em->flush($analytics);
    }

    /**
     *
     */
    public function clear()
    {
        $this->em->clear($this->classNamespace);
    }

    /**
     *
     */
    public function findByEventDate($eventDate)
    {
        return $this->getRepository()->findBy(array('eventDate' => $eventDate));
    }

    /**
     *
     */
    public function findAllByKey($key)
    {
        return $this->getRepository()->findBy(array('eventKey' => $key));
    }

    /**
     *
     * @param string $key
     * @param \DateTime $date
     * @return array
     */
    public function findByKeyAndDate($key, $date)
    {
       return $this->getRepository()->findByKeyAndDate($key, $date);
    }

    /**
     *
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getConnection()
    {
        return $this->em->getConnection();
    }
    /**
     * @return \Effinet\Bundle\DataWarehouseBundle\Repository\ORM\Contact\IdentityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('BoardBundle:Analytics');
    }
}
