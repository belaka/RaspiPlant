<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class I2CMotorsCommand extends ContainerAwareCommand
{

    protected $container;

    protected $input;

    protected $output;

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('raspiplant:motors:manage')
                // the short description shown while running "php bin/console list"
                ->setDescription('Manage I2C Motors.')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp("This command allows you to start or stop I2C motors...")
                ->addArgument('action', InputArgument::REQUIRED, 'What to do with I2C Motors can be (start | stop) ?')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $action = $input->getArgument('action');

        $rootDir = $this->container->getParameter('kernel.root_dir');

        switch ($action) {
            default:
            case "start" :
                $process = new Process('python ' . $rootDir . '/../bin/start-motors-A.py && python ' . $rootDir . '/../bin/start-motors-B.py');
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                echo $process->getOutput();

            return;
            break;
            case "stop" :
                $process = new Process('python ' . $rootDir . '/../bin/stop-motors-A.py && python ' . $rootDir . '/../bin/stop-motors-B.py');
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                echo $process->getOutput();
                return;
            break;
        }

    }

}
