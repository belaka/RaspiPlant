<?php

namespace RaspiPlant\Bundle\BoardBundle\Manager;

use RaspiPlant\Bundle\BoardBundle\Entity\SensorValue;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class SensorValueManager
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
     * SensorValueManager constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = SensorValue::class;
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    /**
     * @param SensorValue $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(SensorValue $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }

    /**
     * @param SensorValue $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(SensorValue $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }

    /**
     * @param SensorValue $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(SensorValue $sensor)
    {
        $this->em->flush($sensor);
    }

    /**
     * @param SensorValue $sensor
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(SensorValue $sensor)
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
     * @param $key
     * @return array
     */
    public function findAllWithKey($key)
    {
        return $this->getRepository()->findAllWithKey($key);
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
        return $this->em->getRepository(SensorValue::class);
    }
}
