<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Wrep\Daemonizable\Command\EndlessContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FullVibes\Component\Device\I2CDevice;
use FullVibes\Component\Sensor;
use FullVibes\Component\WiringPi\WiringPi;

class GroveBME280TestCommand extends EndlessContainerAwareCommand {

    const I2C_UNUSED_VALUE = 0;
    const ISO8601 = 'Y-m-d\TH:i:sP';
    
    /**
     *
     * @var Sensor\BME280Sensor 
     */
    protected  $barometer;

    protected function configure() {
        $this->setName('raspiplant:grove:start')
                ->setDescription('Test GrovePi BME280 test command.')
                ->setHelp("This command allows you to test your BME280 sensor loop...")
                ->setTimeout(10) // Set the timeout in seconds between two calls to the "execute" method
        ;
    }

    // This is a normal Command::initialize() method and it's called exactly once before the first execute call
    protected function initialize(InputInterface $input, OutputInterface $output) {
        
        $debug = false;
        $this->tick = 0;

        $fd = WiringPi::wiringPiI2CSetup(Sensor\BME280Sensor::BME280_ADDRESS);
        $bmeI2c = new I2CDevice($fd);

        $this->barometer = new Sensor\BME280Sensor($bmeI2c, $debug);

        $output->writeln([
            '==========================',
            '  RASPIPLANT BME280 START ',
            '==========================',
            php_uname(),
        ]);

    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        while (true) {
            
            dump($this->barometer->readSensorData());
            sleep(5);
        }
    }

    /**
     * Called after each iteration
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function finishIteration(InputInterface $input, OutputInterface $output) {
        // Do some cleanup/memory management here, don't forget to call the parent implementation!
        parent::finishIteration($input, $output);
    }

    // Called once on shutdown after the last iteration finished
    protected function finalize(InputInterface $input, OutputInterface $output) {
        // Do some cleanup here, don't forget to call the parent implementation!
        parent::finalize($input, $output);
        // Keep it short! We may need to exit because the OS wants to shutdown
        // and we can get killed if it takes to long!
    }

}
