<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DeviceAddCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('raspiplant:device:add')
                // the short description shown while running "php bin/console list"
                ->setDescription('Add device to existing board.')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp("This command allows you to add a device to your board...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

    }

}
