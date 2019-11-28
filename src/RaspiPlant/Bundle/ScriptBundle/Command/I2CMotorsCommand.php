<?php

namespace RaspiPlant\Bundle\ScriptBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class I2CMotorsCommand extends Command implements ScriptCommandInterface
{

    protected $container;

    protected $input;

    protected $output;

    private $params;

    public function __construct(ParameterBagInterface $params)
    {
        $this->params = $params;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('raspiplant:script:motors')
            // the short description shown while running "php bin/console list"
            ->setDescription('Manage I2C Motors.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to start or stop I2C motors...")
            ->addArgument('action', InputArgument::REQUIRED, 'What to do with I2C Motors can be (start | stop) ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $action = $input->getArgument('action');
        $this->validateAction($action);

        $rootDir = $this->params->get('kernel.root_dir');

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
            case "intake" :
                $process = new Process('python ' . $rootDir . '/../bin/stop-motors-intakeA.py && python ' . $rootDir . '/../bin/stop-motors-intakeB.py');
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                echo $process->getOutput();
                return;
                break;
            case "intakeA" :
                $process = new Process('python ' . $rootDir . '/../bin/stop-motors-intakeA.py');
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }

                echo $process->getOutput();
                return;
                break;
            case "intakeB" :
                $process = new Process('python ' . $rootDir . '/../bin/stop-motors-intakeB.py');
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

    public static function getActions()
    {
        return [
            'start',
            'stop',
            'intake',
            'intakeA',
            'intakeB'
        ];
    }

    public function validateAction($action)
    {
        $actions = self::getActions();
        if (!in_array($action, $actions)) {
            throw new \Exception(sprintf('Action %s was not found in this class available actions', $action));
        }
    }

}
