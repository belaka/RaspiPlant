<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Wrep\Daemonizable\Command\EndlessContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FullVibes\Component\Device\I2CDevice;
use FullVibes\Bundle\BoardBundle\Entity\Analytics;
use FullVibes\Component\Sensor;
use FullVibes\Component\Actuator;
use FullVibes\Component\WiringPi\WiringPi;

class GroveStartCommand extends EndlessContainerAwareCommand {

    const RPI_I2C_ADDRESS = 0x04; // I2C Address of Raspberry
    const MOTOR_I2C_ADDRESS_1 = 0x0f;
    const MOTOR_I2C_ADDRESS_2 = 0x0f;
    const I2C_UNUSED_VALUE = 0;
    const ISO8601 = 'Y-m-d\TH:i:sP';

    protected function configure() {
        $this->setName('raspiplant:grove:start')
                ->setDescription('Test GrovePi start command.')
                ->setHelp("This command allows you to start your sensors loop...")
                ->setTimeout(10) // Set the timeout in seconds between two calls to the "execute" method
        ;
    }

    // This is a normal Command::initialize() method and it's called exactly once before the first execute call
    protected function initialize(InputInterface $input, OutputInterface $output) {
        
        $debug = false;
        $this->tick = 0;

        $airQualityPin = 2;
        $moisturePin1 = 0;
        $moisturePin2 = 1;
        $atomizerPin = 5;
        
        $dhtPin = 6;
        
        $bar_fd = WiringPi::wiringPiI2CSetup(Sensor\BME280Sensor::BME280_ADDRESS);
        $bmeI2c = new I2CDevice($bar_fd);
        $this->barometer = new Sensor\BME280Sensor($bmeI2c, $debug);

        $fd = WiringPi::wiringPiI2CSetup(self::RPI_I2C_ADDRESS);
        $grovepi = new I2CDevice($fd);

        $this->moisture1 = new Sensor\MoistureSensor($grovepi, $moisturePin1, $debug);
        $this->moisture2 = new Sensor\MoistureSensor($grovepi, $moisturePin2, $debug);
        $this->airQuality = new Sensor\AirQualitySensor($grovepi, $airQualityPin, $debug);
        $this->temphum = new Sensor\DHTSensor($grovepi, $dhtPin, $debug);
        //$this->atomizer = new Actuator\WaterAtomizationActuator($grovepi, $atomizerPin, $debug);

        $output->writeln([
            '===================',
            '  RASPIPLANT START ',
            '===================',
            php_uname(),
        ]);


        //$this->atomizer->writeStatus(0);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $firedAt = new \DateTime();

        //$this->atomizer->writeStatus(1);

        /* Moisture 1 sensor read */
        $moisture1Value = $this->moisture1->readSensorData();
        usleep(100000);
        /* Moisture 2 sensor read */
        $moisture2Value = $this->moisture2->readSensorData();
        usleep(100000);
        /* Air quality sensor read */
        $airQualityValue = $this->airQuality->readSensorData();
        usleep(100000);


        $temphumValues = json_decode($this->temphum->readSensorData());
        if (!$temphumValues) {
            $temperatureValue = 0;
            $humidityValue = 0;
        } else {
            $temperatureValue = $temphumValues->temperature;
            $humidityValue = $temphumValues->humidity;
        }
        
        usleep(100000);
        
        $barometer = json_decode($this->barometer->readSensorData());
        if (!$barometer) {
            $i2c_temperature = 0;
            $i2c_humidity = 0;
            $i2c_pressure = 0;
            $i2c_dewPoint = 0;
        } else {
            $i2c_temperature = $barometer->temperature;
            $i2c_humidity = $barometer->humidity;
            $i2c_pressure = $barometer->pressure;
            $i2c_dewPoint = $barometer->dew_point;
        }

        $output->writeln("###############################################");
        $output->writeln("#                 RasPiPlant                  #");
        $output->writeln("#                                             #");
        $output->writeln("#" . $firedAt->format(self::ISO8601) . "#");
        $output->writeln("###############################################");

        $output->writeln("Moisture 1 :" . $moisture1Value);
        $output->writeln("Moisture 2 :" . $moisture2Value);
        $output->writeln("Air Quality:" . $airQualityValue);
        $output->writeln("Temperature :" . $temperatureValue . "C°");
        $output->writeln("Humidity :" . $humidityValue . "%");
        
        $output->writeln("I2C Temperature :" . $i2c_temperature . "C°");
        $output->writeln("I2C Humidity :" . $i2c_humidity . "%");
        $output->writeln("I2C Pressure :" . $i2c_pressure . "mBar");
        $output->writeln("I2C Dew Point:" . $i2c_dewPoint . "C°");
        

        $output->writeln("");

        if (($this->tick % 120) == 0) {
            $this->persistValues(
                array(
                    'moisture_1' => $moisture1Value,
                    'moisture_2' => $moisture2Value,
                    'air_quality' => $airQualityValue,
                    'temperature' => $temperatureValue,
                    'humidity' => $humidityValue,
                    'i2c_temperature' => $i2c_temperature,
                    'i2c_humidity' => $i2c_humidity,
                    'i2c_pressure' => $i2c_pressure,
                    'i2c_dewPoint' => $i2c_dewPoint,
                ), 
                $firedAt, 
                $output
            );
        }

        $this->tick += 10;
    }

    protected function persistValues($data, $firedAt, $output) {
        
        $analyticsManager = $this->getAnalyticsManager();
        $output->writeln("Data added to database " . $firedAt->format(self::ISO8601));

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

    /**
     * Called after each iteration
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function finishIteration(InputInterface $input, OutputInterface $output) {
        // Do some cleanup/memory management here, don't forget to call the parent implementation!
        $this->getAnalyticsManager()->clear();
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
