<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
//use FullVibes\Bundle\BoardBundle\Entity\Analytics;
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
        
        $analyticsManager = $this->getAnalyticsManager();
        
        $debug = false;
        
        $airQualityPin = 2;
        $moisturePin1 = 0;
        $moisturePin2 = 1;
//        $atomizerPin = 2;
//        $dhtPin1 = 3;
//        $dhtPin2 = 6;
        
        $fd = wiringpii2csetup(self::RPI_I2C_ADDRESS);
        $grovepi = new I2CDevice($fd);
        
        
        $tick = 0;

        while (true) {
            
            $firedAt = new \DateTime();
            
            /*Moisture 1 sensor read*/
            $moisture1 = new Sensor\MoistureSensor($grovepi, $moisturePin1, $debug);
            $moisture1Value = $moisture1->readSensorData();

            /*Moisture 2 sensor read*/
            $moisture2 = new Sensor\MoistureSensor($grovepi, $moisturePin2, $debug);
            $moisture2Value = $moisture2->readSensorData();
            
            /*Air quality sensor read*/
            $airQuality = new Sensor\AirQualitySensor($grovepi, $airQualityPin, $debug);
            $airQualityValue = $airQuality->readSensorData();
            
            $output->writeln("###############################################");
            $output->writeln("#                 RasPiPlant                  #");
            $output->writeln("#                                             #");
            $output->writeln("#".$firedAt->format(self::ISO8601).                    "#");
            $output->writeln("###############################################");
            //$output->writeln("Light:" . $lightValue);
            $output->writeln("Moisture 1 :" . $moisture1Value);
            $output->writeln("Moisture 2 :" . $moisture2Value);
            $output->writeln("Air Quality:" . $airQualityValue);
            
            $output->writeln("");
            
            sleep(10);
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
