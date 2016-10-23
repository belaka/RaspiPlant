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

        $moisturePin = 0;
        $airQualityPin = 1;
        $lightPin = 2;
        $dhtPin = 8;
        $relay1Pin = 2;
        
        $light = new Sensor\LightSensor($lightPin, $debug);
        $moisture = new Sensor\MoistureSensor($moisturePin, $debug);
        $airquality = new Sensor\AirQualitySensor($airQualityPin, $debug);
        $temphum = new Sensor\DHTSensor($dhtPin, Sensor\DHTSensor::DHT_SENSOR_WHITE);
        $relay1 =  new Actuator\RelayActuator($relay1Pin, $debug);
        $relay1->writeStatus(0);
        
        while (true) {
            
            sleep(3);
            $relay1->writeStatus(0);
            $airQualityValue = $airquality->readSensorData();
            
            if (is_numeric($airQualityValue) && $airQualityValue > 120) {
                $relay1->writeStatus(1);
            }
            
            echo "###############################################\n";
            echo "#       RasPiPlant                            #\n";
            echo "#                                             #\n";
            echo "#                                             #\n";
            echo "###############################################\n";
            echo "Light:" . $light->readSensorData() . "\n";
            echo "Moisture:" . $moisture->readSensorData() . "\n";
            echo "Air Quality:" . $airQualityValue . "\n";
            $temphumValues = $temphum->readSensorData();
            if (!$temphumValues) {
                echo "Temperature:N/A\n";
                echo "Humidity:N/A\n";
            } else {
                echo "Temperature:" . $temphumValues->temperature . "\n";
                echo "Humidity:" . $temphumValues->humidity . "\n";
            }
            
        }
    }

}
