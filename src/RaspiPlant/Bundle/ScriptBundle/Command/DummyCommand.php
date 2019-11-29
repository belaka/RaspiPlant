<?php

namespace RaspiPlant\Bundle\ScriptBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DummyCommand extends Command implements ScriptCommandInterface
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
            ->setName('raspiplant:script:dummy')
            // the short description shown while running "php bin/console list"
            ->setDescription('Dummy script that do nothing.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to test script command...")
            ->addArgument('action', InputArgument::REQUIRED, '(start | stop) ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $action = $input->getArgument('action');
        $this->validateAction($action);

        switch ($action) {
            default:
            case "start" :
                $output->writeln("Dummy script start");

                return;
                break;
            case "stop" :
                $output->writeln("Dummy script stop");

                return;
                break;
        }

    }

    public static function getActions()
    {
        return [
            'start',
            'stop'
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
