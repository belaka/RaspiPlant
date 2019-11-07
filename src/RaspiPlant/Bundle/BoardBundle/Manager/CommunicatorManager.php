<?php

namespace RaspiPlant\Bundle\BoardBundle\Manager;

use RaspiPlant\Bundle\BoardBundle\Entity\Communicator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class CommunicatorManager
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
     * CommunicatorManager constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = Communicator::class;
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    /**
     * @param Communicator $communicator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Communicator $communicator)
    {
        $this->em->persist($communicator);
        $this->em->flush($communicator);
    }

    /**
     * @param Communicator $communicator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Communicator $communicator)
    {
        $this->em->persist($communicator);
        $this->em->flush($communicator);
    }

    /**
     * @param Communicator $communicator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Communicator $communicator)
    {
        $this->em->flush($communicator);
    }

    /**
     * @param Communicator $communicator
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Communicator $communicator)
    {
        $this->em->remove($communicator);
        $this->em->flush($communicator);
    }

    /**
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     */
    public function clear()
    {
        $this->em->clear($this->classNamespace);
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
        return $this->em->getRepository(Communicator::class);
    }
}
