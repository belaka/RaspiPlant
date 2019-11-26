<?php

namespace RaspiPlant\Bundle\BoardBundle\Command;

use RaspiPlant\Bundle\BoardBundle\Manager\BoardManager;
use RaspiPlant\Bundle\DeviceBundle\DependencyInjection\DeviceExtension;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BoardTreeCommand extends Command {

    protected $boardManager;

    public function __construct(BoardManager $boardManager)
    {
        $this->boardManager = $boardManager;

        parent::__construct();
    }

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('raspiplant:board:tree')
                // the short description shown while running "php bin/console list"
                ->setDescription('List actually connected boards.')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp("This command allows you to list your board...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $output->writeln([
            '=======================================================================',
            '========================   RASPIPLANT TREE    ========================',
            '=======================================================================',
            php_uname(),
        ]);

        $boards = $this->boardManager->getRepository()->findAll();

        $output->writeln("BOARDS:");

        $deviceTypes = DeviceExtension::DEVICE_TYPES;

        foreach ($boards as $board) {
            $output->writeln("- " . $board->getId() . " " . $board->getName() . " " . $board->getSlug());
            $output->writeln("  DEVICES:");

            foreach ($deviceTypes as $key => $value) {
                $output->writeln("  - " . $key);
                $method = 'get' . ucfirst($key);

                $devices = $board->{$method}();

                foreach ($devices as $device) {
                    $output->writeln("      - " . $device->getId() .  " " . $device->getName() . " " . $device->getSlug() . " " . $device->getPin());
                }

            }

            $output->writeln("- " . $board->getId() . " " . $board->getName() . " " . $board->getSlug());
            $output->writeln("  SCRIPTS:");
            $scripts = $board->getScripts();
            foreach ($scripts as $script) {
                $output->writeln("  - " . $script->getName());
            }
        }

    }

}
