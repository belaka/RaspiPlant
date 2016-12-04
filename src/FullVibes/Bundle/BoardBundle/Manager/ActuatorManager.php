<?php

namespace FullVibes\Bundle\BoardBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FullVibes\Bundle\BoardBundle\Entity\Actuator;

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
     * @param \Doctrine\Bundle\DoctrineBundle\Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = 'FullVibes\Bundle\BoardBundle\Entity\Actuator';
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Actuator $actuator
     */
    public function create(Actuator $actuator)
    {
        $this->em->persist($actuator);
        $this->em->flush($actuator);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Actuator $actuator
     */
    public function save(Actuator $actuator)
    {
        $this->em->persist($actuator);
        $this->em->flush($actuator);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Actuator $actuator
     */
    public function update(Actuator $actuator)
    {
        $this->em->flush($actuator);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Actuator $actuator
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
     * 
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
     * @return \Effinet\Bundle\DataWarehouseBundle\Repository\ORM\Contact\IdentityRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('BoardBundle:Actuator');
    }
}