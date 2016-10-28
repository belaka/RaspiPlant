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

    const ISO8601 = 'Y-m-d\TH:i:sP';
    
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
        $atomizerPin = 2;
        
        $light = new Sensor\LightSensor($lightPin, $debug);
        $moisture = new Sensor\MoistureSensor($moisturePin, $debug);
        $airQuality = new Sensor\AirQualitySensor($airQualityPin, $debug);
        $temphum = new Sensor\DHTSensor($dhtPin, Sensor\DHTSensor::DHT_SENSOR_WHITE);
        
        $atomizer =  new Actuator\WaterAtomizationActuator($atomizerPin, $debug);
        $atomizer->writeStatus(0);
        
        $motorDriver = new Actuator\MotorDriverActuator();
        
        $tick = 0;
        
        while (true) {
            
            $motorDriver->motorSpeedSetAB(100,100);
	    $motorDriver->motorDirectionSet(0b1010);
            sleep(2);
            $motorDriver->motorSpeedSetAB(100,100);
            $motorDriver->motorDirectionSet(0b0101);
	    sleep(2);
            $motorDriver->motorSpeedSetAB(0,0);
            
            $atomizer->writeStatus(1);
            sleep(10);
            $atomizer->writeStatus(0);
            $firedAt = new \DateTime();
            
            /*Light sensor read*/
            $lightValue = $light->readSensorData();
            
            /*Moisture sensor read*/
            $moistureValue = $moisture->readSensorData();
                        
            /*Air quality sensor read*/
            $airQualityValue = $airQuality->readSensorData();
            
            $temphumValues = json_decode($temphum->readSensorData());
            if (!$temphumValues) {
                $temperatureValue = 0;
                $humidityValue = 0;
                
            } else {
                $temperatureValue = $temphumValues->temperature;
                $humidityValue = $temphumValues->humidity;
            }
            
            if (($tick % 120) == 0)  {
                
                /*Light value store*/
                $lightAnalytics = new Analytics('light', $lightValue, $firedAt);
                $analyticsManager->save($lightAnalytics);

                /*Moisture value store*/
                $moistureAnalytics = new Analytics('moisture', $moistureValue, $firedAt);
                $analyticsManager->save($moistureAnalytics);

                /*Air quality value store*/
                $airQualityAnalytics = new Analytics('air_quality', $airQualityValue, $firedAt);
                $analyticsManager->save($airQualityAnalytics);

                /*Temperature value store*/
                $temperatureAnalytics = new Analytics('temperature', $temperatureValue, $firedAt);
                $analyticsManager->save($temperatureAnalytics);

                /*Humidity value store*/
                $humidityAnalytics = new Analytics('humidity', $humidityValue, $firedAt);
                $analyticsManager->save($humidityAnalytics);
            }
            
            $output->writeln("###############################################");
            $output->writeln("#                 RasPiPlant                  #");
            $output->writeln("#                                             #");
            $output->writeln("#".$firedAt->format(self::ISO8601)."#");
            $output->writeln("###############################################");
            $output->writeln("Light:" . $lightValue);
            $output->writeln("Moisture:" . $moistureValue);
            $output->writeln("Air Quality:" . $airQualityValue);
            $output->writeln("Temperature:" . $temperatureValue . "C°");
            $output->writeln("Humidity:" . $humidityValue . "%");
            $output->writeln("");
            
            $tick += 10;
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
