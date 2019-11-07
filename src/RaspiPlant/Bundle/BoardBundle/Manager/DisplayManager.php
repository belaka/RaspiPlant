<?php

namespace RaspiPlant\Bundle\BoardBundle\Manager;

use RaspiPlant\Bundle\BoardBundle\Entity\Display;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class DisplayManager
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
     * DisplayManager constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = Display::class;
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    /**
     * @param Display $display
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function create(Display $display)
    {
        $this->em->persist($display);
        $this->em->flush($display);
    }

    /**
     * @param Display $display
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(Display $display)
    {
        $this->em->persist($display);
        $this->em->flush($display);
    }

    /**
     * @param Display $display
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Display $display)
    {
        $this->em->flush($display);
    }

    /**
     * @param Display $display
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function remove(Display $display)
    {
        $this->em->remove($display);
        $this->em->flush($display);
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
        return $this->em->getRepository(Display::class);
    }
}
