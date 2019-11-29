<?php


namespace RaspiPlant\Component\EventSubscriber;

use RaspiPlant\Bundle\ScriptBundle\Entity\Script;
use RaspiPlant\Component\Event\DeviceEvents;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class DeviceEventSubscriber implements EventSubscriberInterface
{
    /** @var  KernelInterface */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

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
        $device = $event->getSubject();
        $scripts = $device->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === DeviceEvents::DEVICE_PRE_START && $script->isActive()) {
                $this->runDeviceScript($script);
            }
        }
    }

    public function afterStart(GenericEvent $event): void
    {
        $device = $event->getSubject();
        $scripts = $device->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === DeviceEvents::DEVICE_POST_START && $script->isActive()) {
                $this->runDeviceScript($script);
            }
        }
    }

    public function beforeStop(GenericEvent $event): void
    {
        $device = $event->getSubject();
        $scripts = $device->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === DeviceEvents::DEVICE_PRE_STOP && $script->isActive()) {
                $this->runDeviceScript($script);
            }
        }
    }

    public function afterStop(GenericEvent $event): void
    {
        $device = $event->getSubject();
        $scripts = $device->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === DeviceEvents::DEVICE_POST_STOP && $script->isActive()) {
                $this->runDeviceScript($script);
            }
        }
    }

    public function afterCall(GenericEvent $event): void
    {
        $device = $event->getSubject();
        $scripts = $device->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === DeviceEvents::DEVICE_POST_CALL && $script->isActive()) {
                $this->runDeviceScript($script);
            }
        }
    }

    public function beforeCall(GenericEvent $event): void
    {
        $device = $event->getSubject();
        $scripts = $device->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === DeviceEvents::DEVICE_PRE_CALL && $script->isActive()) {
                $this->runDeviceScript($script);
            }
        }
    }

    private function runDeviceScript(Script $script)
    {
        $scriptCommand = explode(' ', $script->getScript());

        $application = new Application($this->kernel);
        $command = $application->find($scriptCommand[0]);
        $arguments = array(
            'command' => $scriptCommand[0],
            'action' => $scriptCommand[1]
        );
        $commandInput = new ArrayInput($arguments);
        $commandOutput = new ConsoleOutput();
        $command->run($commandInput, $commandOutput);
    }
}
