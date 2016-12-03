<?php

namespace FullVibes\Bundle\BoardBundle\Manager;

use Doctrine\Bundle\DoctrineBundle\Registry;
use FullVibes\Bundle\BoardBundle\Entity\Board;

/**
 * @author Vincent Honnorat <vincenth@effi-net.com>
 */
class BoardManager
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
        $this->classNamespace = 'FullVibes\Bundle\BoardBundle\Entity\Board';
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Board $board
     */
    public function create(Board $board)
    {
        $this->em->persist($board);
        $this->em->flush($board);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Board $board
     */
    public function save(Board $board)
    {
        $this->em->persist($board);
        $this->em->flush($board);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Board $board
     */
    public function update(Board $board)
    {
        $this->em->flush($board);
    }
    
    /**
     * @param \FullVibes\Bundle\BoardBundle\Entity\Board $board
     */
    public function remove(Board $board)
    {
        $this->em->remove($board);
        $this->em->flush($board);
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
        return $this->em->getRepository('BoardBundle:Board');
    }
}