<?php

namespace RaspiPlant\Bundle\BoardBundle\Manager;

use RaspiPlant\Bundle\BoardBundle\Entity\Sensor;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class SensorManager
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
     * SensorManager constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = Sensor::class;
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    /**
     * @param Sensor $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Sensor $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }

    /**
     * @param Sensor $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Sensor $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }

    /**
     * @param Sensor $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Sensor $sensor)
    {
        $this->em->flush($sensor);
    }

    /**
     * @param Sensor $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Sensor $sensor)
    {
        $this->em->remove($sensor);
        $this->em->flush($sensor);
    }

    /**
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function clear()
    {
        $this->em->clear($this->classNamespace);
    }

    /**
     * @return array|object[]
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param $id
     * @return object|null
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @return array
     */
    public function findAllActive()
    {
        return $this->getRepository()->findAllActive();
    }

    /**
     * @return \Doctrine\ORM\EntityManager
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
        return $this->em->getRepository(Sensor::class);
    }
}
