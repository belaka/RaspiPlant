<?php

namespace FullVibes\Bundle\BoardBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FullVibes\Bundle\BoardBundle\Entity\SensorValue;

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
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = 'FullVibes\Bundle\BoardBundle\Entity\SensorValue';
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\SensorValue $sensor
     */
    public function create(SensorValue $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\SensorValue $sensor
     */
    public function save(SensorValue $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\SensorValue $sensor
     */
    public function update(SensorValue $sensor)
    {
        $this->em->flush($sensor);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\SensorValue $sensor
     */
    public function remove(SensorValue $sensor)
    {
        $this->em->remove($sensor);
        $this->em->flush($sensor);
    }

    /**
     *
     */
    public function clear()
    {
        $this->em->clear($this->classNamespace);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * @param $key
     * @return mixed
     */
    public function findAllWithKey($key)
    {
        return $this->getRepository()->findAllWithKey($key);
    }

    /**
     * @param $id
     * @return null|object
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getConnection()
    {
        return $this->em->getConnection();
    }

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository|\FullVibes\Bundle\BoardBundle\Repository\ORM\SensorValueRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('SensorValue');
    }
}