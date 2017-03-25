<?php

namespace FullVibes\Bundle\BoardBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FullVibes\Bundle\BoardBundle\Entity\Sensor;

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
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = 'FullVibes\Bundle\BoardBundle\Entity\Sensor';
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Sensor $sensor
     */
    public function create(Sensor $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Sensor $sensor
     */
    public function save(Sensor $sensor)
    {
        $this->em->persist($sensor);
        $this->em->flush($sensor);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Sensor $sensor
     */
    public function update(Sensor $sensor)
    {
        $this->em->flush($sensor);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Sensor $sensor
     */
    public function remove(Sensor $sensor)
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
     * @return Sensor
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
     * @return \Doctrine\Common\Persistence\ObjectRepository|\Doctrine\ORM\EntityRepository|\FullVibes\Bundle\BoardBundle\Repository\ORM\SensorRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('BoardBundle:Sensor');
    }
}