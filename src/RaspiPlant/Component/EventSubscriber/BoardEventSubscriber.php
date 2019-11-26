<?php


namespace RaspiPlant\Component\EventSubscriber;

use RaspiPlant\Component\Event\BoardEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

class BoardEventSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            BoardEvents::BOARD_PRE_START => 'beforeStart',
            BoardEvents::BOARD_POST_START => 'afterStart',
            BoardEvents::BOARD_PRE_STOP => 'beforeStop',
            BoardEvents::BOARD_POST_STOP => 'afterStop'
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
}
