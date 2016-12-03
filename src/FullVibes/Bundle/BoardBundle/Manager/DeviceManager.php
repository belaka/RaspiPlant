<?php

namespace FullVibes\Bundle\BoardBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FullVibes\Bundle\BoardBundle\Entity\Device;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class DeviceManager
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
        $this->classNamespace = 'FullVibes\Bundle\BoardBundle\Entity\Device';
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Device $device
     */
    public function create(Device $device)
    {
        $this->em->persist($device);
        $this->em->flush($device);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Device $device
     */
    public function save(Device $device)
    {
        $this->em->persist($device);
        $this->em->flush($device);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Device $device
     */
    public function update(Device $device)
    {
        $this->em->flush($device);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Device $device
     */
    public function remove(Device $device)
    {
        $this->em->remove($device);
        $this->em->flush($device);
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
        return $this->em->getRepository('BoardBundle:Device');
    }
}