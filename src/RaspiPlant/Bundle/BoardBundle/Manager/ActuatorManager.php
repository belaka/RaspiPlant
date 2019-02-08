<?php

namespace RaspiPlant\Bundle\BoardBundle\Manager;

use RaspiPlant\Bundle\BoardBundle\Entity\Actuator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class ActuatorManager
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
     * ActuatorManager constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = Actuator::class;
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    /**
     * @param Actuator $actuator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Actuator $actuator)
    {
        $this->em->persist($actuator);
        $this->em->flush($actuator);
    }

    /**
     * @param Actuator $actuator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Actuator $actuator)
    {
        $this->em->persist($actuator);
        $this->em->flush($actuator);
    }

    /**
     * @param Actuator $actuator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Actuator $actuator)
    {
        $this->em->flush($actuator);
    }

    /**
     * @param Actuator $actuator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Actuator $actuator)
    {
        $this->em->remove($actuator);
        $this->em->flush($actuator);
    }

    /**
     *
     */
    public function clear()
    {
        $this->em->clear($this->classNamespace);
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
     * @return array|object[]
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
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository(Actuator::class);
    }
}
