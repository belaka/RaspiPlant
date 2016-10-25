<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FullVibes\Bundle\BoardBundle\Entity\Analytics;
use FullVibes\Component\Sensor;
use FullVibes\Component\Actuator;
use FullVibes\Component\Display;

class GrovePiCommand extends ContainerAwareCommand {

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
        
        $analyticsManager = $this->getAnalyticsManager();
        
        //$wiringFuncs = get_extension_funcs('wiringpi');
        //foreach($wiringFuncs as $wiringFunc) {
        //	$output->writeln($wiringFunc);
        //}

        $debug = false;

        $moisturePin = 0;
        $airQualityPin = 1;
        $lightPin = 2;
        $dhtPin = 8;
        //$relay1Pin = 2;
        
        $light = new Sensor\LightSensor($lightPin, $debug);
        $moisture = new Sensor\MoistureSensor($moisturePin, $debug);
        $airQuality = new Sensor\AirQualitySensor($airQualityPin, $debug);
        $temphum = new Sensor\DHTSensor($dhtPin, Sensor\DHTSensor::DHT_SENSOR_WHITE);
        
        //$relay1 =  new Actuator\RelayActuator($relay1Pin, $debug);
        //$relay1->writeStatus(0);
        
        while (true) {
            
            sleep(60);
            $firedAt = new \DateTime();
            
            /*Light get and store*/
            $lightValue = $light->readSensorData();
            $lightAnalytics = new Analytics('light', $lightValue, $firedAt);
            $analyticsManager->save($lightAnalytics);
            
            /*Moisture get and store*/
            $moistureValue = $moisture->readSensorData();
            $moistureAnalytics = new Analytics('moisture', $moistureValue, $firedAt);
            $analyticsManager->save($moistureAnalytics);
            
            /*Air quality get and store*/
            $airQualityValue = $airQuality->readSensorData();
            $airQualityAnalytics = new Analytics('air_quality', $airQualityValue, $firedAt);
            $analyticsManager->save($airQualityAnalytics);
            
            $temphumValues = json_decode($temphum->readSensorData());
            if (!$temphumValues) {
                $temperatureValue = 0;
                $humidityValue = 0;
                
            } else {
                $temperatureValue = $temphumValues->temperature;
                $humidityValue = $temphumValues->humidity;
            }
            
            /*Temperature get and store*/
            $temperatureAnalytics = new Analytics('temperature', $temperatureValue, $firedAt);
            $analyticsManager->save($temperatureAnalytics);
            
            /*Humidity get and store*/
            $humidityAnalytics = new Analytics('humidity', $humidityValue, $firedAt);
            $analyticsManager->save($humidityAnalytics);
            
            
            echo "###############################################\n";
            echo "#       RasPiPlant                            #\n";
            echo "#                                             #\n";
            echo "#                                             #\n";
            echo "###############################################\n";
            echo "Light:" . $lightValue . "\n";
            echo "Moisture:" . $moistureValue . "\n";
            echo "Air Quality:" . $airQualityValue . "\n";
            echo "Temperature:" . $temperatureValue . "\n";
            echo "Humidity:" . $humidityValue . "\n";
            echo "\n";
            
        }
    }
    
    /**
     * 
     * @return \FullVibes\Bundle\BoardBundle\Manager\AnalyticsManager
     */
    protected function getAnalyticsManager() {
        
        return $this->getContainer()->get("board.manager.analytics_manager");
        
    }

}
