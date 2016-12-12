<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BoardListCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('raspiplant:board:list')
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
            '========================   RASPIPLANT START    ========================',
            '=======================================================================',
            php_uname(),
        ]);
        
        $boardManager = $this->getBoardManager();
        $boards = $boardManager->getRepository()->findAll();

        $output->writeln("BOARD:");
        foreach ($boards as $board) {
            $output->writeln("- " . $board->getId() . " " . $board->getName() . " " . $board->getSlug());
            $output->writeln("  DEVICES:");
            $devices = $board->getDevices();
            foreach ($devices as $device) {
                $output->writeln("  - " . $device->getId() . " " . $device->getName() . " " . $device->getSlug() . " " . $device->getAddress());
                $output->writeln("      SENSORS:");
                $sensors = $device->getSensors();
                foreach ($sensors as $sensor) {
                    $output->writeln("      - " . $sensor->getId() .  " " . $sensor->getName() . " " . $sensor->getSlug() . " " . $sensor->getPin());
                }
                $output->writeln("      ACTUATORS:");
                $actuators = $device->getActuators();
                foreach ($actuators as $actuator) {
                    $output->writeln("      - " . $actuator->getId() . " " . $actuator->getName() . " " . $actuator->getSlug() . " " . $actuator->getPin());
                }
            }
        }

    }
    
    /**
     * 
     * @return \FullVibes\Bundle\BoardBundle\Manager\AnalyticsManager
     */
    protected function getBoardManager() {
        
        return $this->getContainer()->get("board.manager.board_manager");
        
    }

}
