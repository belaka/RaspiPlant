<?php

namespace RaspiPlant\Component\EventSubscriber;

use RaspiPlant\Bundle\ScriptBundle\Entity\Script;
use RaspiPlant\Component\Event\BoardEvents;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\KernelInterface;

class BoardEventSubscriber implements EventSubscriberInterface
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
            BoardEvents::BOARD_PRE_START => 'beforeStart',
            BoardEvents::BOARD_POST_START => 'afterStart',
            BoardEvents::BOARD_PRE_STOP => 'beforeStop',
            BoardEvents::BOARD_POST_STOP => 'afterStop'
        ];
    }

    public function beforeStart(GenericEvent $event): void
    {
        $board = $event->getSubject();
        $scripts = $board->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === BoardEvents::BOARD_PRE_START && $script->isActive()) {
                $this->runBoardScript($script);
            }
        }
    }

    public function afterStart(GenericEvent $event): void
    {
        $board = $event->getSubject();
        $scripts = $board->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === BoardEvents::BOARD_POST_START && $script->isActive()) {
                $this->runBoardScript($script);
            }
        }
    }

    public function beforeStop(GenericEvent $event): void
    {
        $board = $event->getSubject();
        $scripts = $board->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === BoardEvents::BOARD_PRE_STOP && $script->isActive()) {
                $this->runBoardScript($script);
            }
        }
    }

    public function afterStop(GenericEvent $event): void
    {
        $board = $event->getSubject();
        $scripts = $board->getScripts();
        foreach ($scripts as $script) {
            if ($script->getEvent() === BoardEvents::BOARD_POST_STOP && $script->isActive()) {
                $this->runBoardScript($script);
            }
        }
    }

    private function runBoardScript(Script $script)
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
