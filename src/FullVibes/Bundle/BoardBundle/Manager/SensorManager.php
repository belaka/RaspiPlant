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
        return $this->em->getRepository('BoardBundle:Sensor');
    }
}