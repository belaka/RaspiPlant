<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FullVibes\Component\Sensor;
use FullVibes\Component\Actuator;
use FullVibes\Component\Display;

class GrovePiCommand extends Command {

    protected function configure() {
        $this
                // the name of the command (the part after "bin/console")
                ->setName('grove:test')
                // the short description shown while running "php bin/console list"
                ->setDescription('Test GrovePi connection.')
                // the full command description shown when running the command with
                // the "--help" option
                ->setHelp("This command allows you to test your grove installation...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '===================',
            'Weedid Sensors Test',
            '===================',
            php_uname(),
        ]);
        
        //$wiringFuncs = get_extension_funcs('wiringpi');
        //foreach($wiringFuncs as $wiringFunc) {
        //	$output->writeln($wiringFunc);
        //}

        $debug = false;

        $analogPin = 0;
        
        $display = new Sensor\MoistureSensor($debug);

        while (true) {
            
            sleep(1);
            dump($display->readSensorData());
        }
    }

}
