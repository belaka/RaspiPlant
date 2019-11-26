<?php


namespace RaspiPlant\Component\EventSubscriber;

use RaspiPlant\Component\Event\DeviceEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class DeviceEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            DeviceEvents::DEVICE_PRE_START => 'beforeStart',
            DeviceEvents::DEVICE_POST_START => 'afterStart',
            DeviceEvents::DEVICE_PRE_STOP => 'beforeStop',
            DeviceEvents::DEVICE_POST_STOP => 'afterStop',
            DeviceEvents::DEVICE_PRE_CALL => 'beforeCall',
            DeviceEvents::DEVICE_POST_CALL => 'afterCall'
        ];
    }

    public function beforeStart(GenericEvent $event): void
    {

    }

    public function afterStart(GenericEvent $event): void
    {

    }

    public function beforeStop(GenericEvent $event): void
    {

    }

    public function afterStop(GenericEvent $event): void
    {

    }

    public function afterCall(GenericEvent $event): void
    {

    }

    public function beforeCall(GenericEvent $event): void
    {

    }
}
