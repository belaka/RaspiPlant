<?php

namespace FullVibes\Bundle\BoardBundle\Listener;

use Doctrine\ORM\Event\LifecycleEventArgs;
use FullVibes\Component\Traits\UtilsTrait;

class EntityListener
{
    use UtilsTrait;

    /**
     *
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->applyCallbacks($args, "prePersist");
    }

    /**
     *
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->applyCallbacks($args, "preUpdate");
    }

    /**
     * @param LifecycleEventArgs $args
     * @param $eventContext
     */
    protected function applyCallbacks(LifecycleEventArgs $args, $eventContext)
    {
        $entity = $args->getEntity();
        if (method_exists ( $entity , 'setSlug' ) && property_exists($entity , 'name' )) {
            $entity->setSlug($this->makeSlug(trim($entity->getName())));
        }
    }
}