<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FullVibes\Component\Device\I2CDevice;
use FullVibes\Bundle\BoardBundle\Entity\Analytics;
use FullVibes\Component\Sensor;

class GroveStartCommand extends ContainerAwareCommand {

    const RPI_I2C_ADDRESS = 0x04; // I2C Address of Raspberry
    
    const I2C_UNUSED_VALUE = 0;
    
    const ISO8601 = 'Y-m-d\TH:i:sP';
    
    protected function configure() {
        $this->setName('raspiplant:grove:start')
            ->setDescription('Test GrovePi start command.')
            ->setHelp("This command allows you to start your sensors loop...")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln([
            '===================',
            '  RASPIPLANT START ',
            '===================',
            php_uname(),
        ]);
        
        $debug = false;
        
        $airQualityPin = 2;
        $moisturePin1 = 0;
        $moisturePin2 = 1;
//        $atomizerPin = 2;
        $dhtPin1 = 3;
        $dhtPin2 = 6;
        
        $fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $grovepi = new I2CDevice($fd);
        
        $moisture1 = new Sensor\MoistureSensor($grovepi, $moisturePin1, $debug);
        $moisture2 = new Sensor\MoistureSensor($grovepi, $moisturePin2, $debug);
        $airQuality = new Sensor\AirQualitySensor($grovepi, $airQualityPin, $debug);
        $temphum1 = new Sensor\DHTSensor($grovepi, $dhtPin1, Sensor\DHTSensor::DHT_SENSOR_WHITE);
        $temphum2 = new Sensor\DHTSensor($grovepi, $dhtPin2, Sensor\DHTSensor::DHT_SENSOR_WHITE);
        
        $tick = 0;

        while (true) {
            
            $firedAt = new \DateTime();
            
            
            
            /*Moisture 1 sensor read*/
            $moisture1Value = $moisture1->readSensorData();
            usleep(1800);
            /*Moisture 2 sensor read*/
            $moisture2Value = $moisture2->readSensorData();
            usleep(1800);
            /*Air quality sensor read*/
            $airQualityValue = $airQuality->readSensorData();
            usleep(1800);
            $temphum1Values = json_decode($temphum1->readSensorData());
            if (!$temphum1Values) {
                $temperature1Value = 0;
                $humidity1Value = 0;
                
            } else {
                $temperature1Value = $temphum1Values->temperature;
                $humidity1Value = $temphum1Values->humidity;
            }
            usleep(1800);
            $temphum2Values = json_decode($temphum2->readSensorData());
            if (!$temphum2Values) {
                $temperature2Value = 0;
                $humidity2Value = 0;
                
            } else {
                $temperature2Value = $temphum2Values->temperature;
                $humidity2Value = $temphum2Values->humidity;
            }
            usleep(1800);
                        
            $output->writeln("###############################################");
            $output->writeln("#                 RasPiPlant                  #");
            $output->writeln("#                                             #");
            $output->writeln("#".$firedAt->format(self::ISO8601).                    "#");
            $output->writeln("###############################################");
            
            $output->writeln("Moisture 1 :" . $moisture1Value);
            $output->writeln("Moisture 2 :" . $moisture2Value);
            $output->writeln("Air Quality:" . $airQualityValue);
            $output->writeln("Temperature 1 :" . $temperature1Value . "C°");
            $output->writeln("Humidity 1 :" . $humidity1Value . "%");
            $output->writeln("Temperature 2 :" . $temperature2Value . "C°");
            $output->writeln("Humidity 2 :" . $humidity2Value . "%");
            $output->writeln("");
            
            if (($tick % 120) == 0)  {
                $this->persistValues(array(
                    'moisture_1' => $moisture1Value,
                    'moisture_2' => $moisture2Value,
                    'air_quality' => $airQualityValue,
                    'temperature_1' => $temperature1Value,
                    'humidity 1' => $humidity1Value,
                    'temperature_2' => $temperature2Value,
                    'humidity 2' => $humidity2Value
                ), $firedAt, $output);
            }
            
            sleep(10);
            $tick += 10;
        }
    }
    
    protected function persistValues($data, $firedAt, $output)
    {
        $analyticsManager = $this->getAnalyticsManager();
        $output->writeln("Data added to database " .  $firedAt->format(self::ISO8601));                

        foreach ($data as $key => $value) {
            $analytics = new Analytics($key, $value, $firedAt);
            $analyticsManager->save($analytics);
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
