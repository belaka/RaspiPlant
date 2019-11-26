<?php

namespace RaspiPlant\Bundle\ScriptBundle\Manager;

use RaspiPlant\Bundle\ScriptBundle\Entity\Script;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class ScriptManager
 * @package RaspiPlant\Bundle\ScriptBundle\Manager
 */
class ScriptManager
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
     * BoardManager constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        $this->em = $registry->getManager();
        $this->classNamespace = Script::class;
        $this->metadata = $this->em->getClassMetadata($this->classNamespace);
    }

    //--------------------------------------------------------------------------
    // Call
    //--------------------------------------------------------------------------

    /**
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->em->$name(...$arguments);
    }

    /**
     * @return array
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
        return $this->em->getRepository(Script::class);
    }
}
