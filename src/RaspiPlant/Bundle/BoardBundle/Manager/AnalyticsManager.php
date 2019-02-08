<?php

namespace RaspiPlant\Bundle\BoardBundle\Manager;

use RaspiPlant\Bundle\BoardBundle\Entity\Analytics;
use Symfony\Bridge\Doctrine\RegistryInterface;

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
     * AnalyticsManager constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = Analytics::class;
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    /**
     * @param Analytics $analytics
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Analytics $analytics)
    {
        $this->em->persist($analytics);
        $this->em->flush($analytics);
    }

    /**
     * @param Analytics $analytics
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Analytics $analytics)
    {
        $this->em->persist($analytics);
        $this->em->flush($analytics);
    }

    /**
     * @param Analytics $analytics
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Analytics $analytics)
    {
        $this->em->flush($analytics);
    }

    /**
     * @param Analytics $analytics
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Analytics $analytics)
    {
        $this->em->remove($analytics);
        $this->em->flush($analytics);
    }

    /**
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function clear()
    {
        $this->em->clear($this->classNamespace);
    }

    /**
     * @param $eventDate
     * @return mixed
     */
    public function findByEventDate($eventDate)
    {
        return $this->getRepository()->findBy(array('eventDate' => $eventDate));
    }

    /**
     * @param $key
     * @return mixed
     */
    public function findAllByKey($key)
    {
        return $this->getRepository()->findBy(array('eventKey' => $key));
    }

    /**
     * @param $key
     * @param $date
     * @return mixed
     */
    public function findByKeyAndDate($key, $date)
    {
       return $this->getRepository()->findByKeyAndDate($key, $date);
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->em->getConnection();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(Analytics::class);
    }
}
