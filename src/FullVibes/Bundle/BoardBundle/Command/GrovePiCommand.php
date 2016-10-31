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
        
        $airQualityPin = 2;
        $moisturePin1 = 0;
        $moisturePin2 = 1;
        $atomizerPin = 2;
        $dhtPin1 = 3;
        $dhtPin2 = 6;
        
        
        
        
        //$motorDriver = new Actuator\MotorDriverActuator();
        
        $tick = 0;
        
        while (true) {
            
            //$light = new Sensor\LightSensor($lightPin, $debug);
            
            
            
            
            
//            $atomizer =  new Actuator\WaterAtomizationActuator($atomizerPin, $debug);
//            $atomizer->writeStatus(0);
            
//            $motorDriver->motorSpeedSetAB(100,100);
//	    $motorDriver->motorDirectionSet(0b1010);
//            sleep(2);
//            $motorDriver->motorSpeedSetAB(100,100);
//            $motorDriver->motorDirectionSet(0b0101);
//	    sleep(2);
//            $motorDriver->motorSpeedSetAB(0,0);
            
//            $atomizer->writeStatus(1);
            sleep(10);
//            $atomizer->writeStatus(0);
            $firedAt = new \DateTime();
            
            /*Light sensor read*/
            //$lightValue = $light->readSensorData();
            
            /*Moisture 1 sensor read*/
            $moisture1 = new Sensor\MoistureSensor($moisturePin1, $debug);
            $moisture1Value = $moisture1->readSensorData();
            /*Moisture 2 sensor read*/
            $moisture2 = new Sensor\MoistureSensor($moisturePin2, $debug);
            $moisture2Value = $moisture2->readSensorData();
                        
            /*Air quality sensor read*/
            $airQuality = new Sensor\AirQualitySensor($airQualityPin, $debug);
            $airQualityValue = $airQuality->readSensorData();
            
            /*Temperature/Humidity 1 sensor read*/
            $temphum1 = new Sensor\DHTSensor($dhtPin1, Sensor\DHTSensor::DHT_SENSOR_WHITE);
            $temphum1Values = json_decode($temphum1->readSensorData());
            if (!$temphum1Values) {
                $temperature1Value = 0;
                $humidity1Value = 0;
                
            } else {
                $temperature1Value = $temphum1Values->temperature;
                $humidity1Value = $temphum1Values->humidity;
            }
            
            //sleep(1);
            
            /*Temperature/Humidity 2 sensor read*/
            $temphum2 = new Sensor\DHTSensor($dhtPin2, Sensor\DHTSensor::DHT_SENSOR_WHITE);
            $temphum2Values = json_decode($temphum2->readSensorData());
            if (!$temphum2Values) {
                $temperature2Value = 0;
                $humidity2Value = 0;
                
            } else {
                $temperature2Value = $temphum2Values->temperature;
                $humidity2Value = $temphum2Values->humidity;
            }
            
            if (($tick % 120) == 0)  {
                
                /*Light value store*/
//                $lightAnalytics = new Analytics('light', $lightValue, $firedAt);
//                $analyticsManager->save($lightAnalytics);

                /*Moisture value store*/
                $moisture1Analytics = new Analytics('moisture_1', $moisture1Value, $firedAt);
                $analyticsManager->save($moisture1Analytics);
                
                /*Moisture value store*/
                $moisture2Analytics = new Analytics('moisture_2', $moisture2Value, $firedAt);
                $analyticsManager->save($moisture2Analytics);

                /*Air quality value store*/
                $airQualityAnalytics = new Analytics('air_quality', $airQualityValue, $firedAt);
                $analyticsManager->save($airQualityAnalytics);

                /*Temperature value store*/
                $temperature1Analytics = new Analytics('temperature_1', $temperature1Value, $firedAt);
                $analyticsManager->save($temperature1Analytics);

                /*Humidity value store*/
                $humidity1Analytics = new Analytics('humidity_1', $humidity1Value, $firedAt);
                $analyticsManager->save($humidity1Analytics);
                
                /*Temperature value store*/
                $temperature2Analytics = new Analytics('temperature_2', $temperature2Value, $firedAt);
                $analyticsManager->save($temperature2Analytics);

                /*Humidity value store*/
                $humidity2Analytics = new Analytics('humidity_2', $humidity2Value, $firedAt);
                $analyticsManager->save($humidity2Analytics);
            }
            
            $output->writeln("###############################################");
            $output->writeln("#                 RasPiPlant                  #");
            $output->writeln("#                                             #");
            $output->writeln("#".$firedAt->format(self::ISO8601).                    "#");
            $output->writeln("###############################################");
            //$output->writeln("Light:" . $lightValue);
            $output->writeln("Moisture 1 :" . $moisture1Value);
            $output->writeln("Moisture 2 :" . $moisture2Value);
            $output->writeln("Air Quality:" . $airQualityValue);
            $output->writeln("Temperature 1 :" . $temperature1Value . "C°");
            $output->writeln("Humidity 1 :" . $humidity1Value . "%");
            $output->writeln("Temperature 2 :" . $temperature2Value . "C°");
            $output->writeln("Humidity 2 :" . $humidity2Value . "%");
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
