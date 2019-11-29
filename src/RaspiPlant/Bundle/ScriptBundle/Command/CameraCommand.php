<?php

namespace RaspiPlant\Bundle\ScriptBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CameraCommand extends Command implements ScriptCommandInterface
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
            ->setName('raspiplant:script:camera')
            // the short description shown while running "php bin/console list"
            ->setDescription('Manage Camera.')
            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to take picture from camera...")
            ->addArgument('action', InputArgument::REQUIRED, 'What to do with camera can be (take|?) ?');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $action = $input->getArgument('action');
        $this->validateAction($action);

        $rootDir = $this->params->get('kernel.root_dir');

        switch ($action) {
            default:
            case "take" :
                //Tak a picture in folder web/motion
                // raspistill -hf -vf -n -w 1024 -h 768 -q 100 -o $(date +"%Y-%m-%d_%H%M").jpg
                $imgDirectory = $this->params->get('stills_directory');
                $imgPath = $imgDirectory . '/' . date('Y-m-d_Hi') . ".jpg";
                $stillCommand = "raspistill -hf -vf -n -w 1024 -h 768 -q 100 -o " . $imgPath;
                $process = new Process($stillCommand);
                $process->run();

                // executes after the command finishes
                if (!$process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                $date = new \DateTime();
                $output->writeln("Image added to directory motion at:" . $date->format(self::ISO8601));

                return;
                break;
        }

    }

    public static function getActions()
    {
        return [
            'take'
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
