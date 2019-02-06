<?php

namespace RaspiPlant\Bundle\BoardBundle\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use RaspiPlant\Bundle\BoardBundle\Event;

/**
 * Description of EventSubscriber
 *
 * @author Vincent Honnorat <creationforge@gmail.com>
 */
class EventSubscriber implements EventSubscriberInterface
{
    /**
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return array(
            Event\AtomizerActuatorEvent::NAME => 'onAtomizerChange',
        );
    }

    /**
     *
     * @param \RaspiPlant\Bundle\BoardBundle\Event\AtomizerActuatorEvent $event
     * @throws \Exception
     */
    public function onAtomizerChange(Event\AtomizerActuatorEvent $event)
    {
        $device = $event->getDevice();
        $value = $event->getValue();

        $device->writeStatus($value);
    }

}

