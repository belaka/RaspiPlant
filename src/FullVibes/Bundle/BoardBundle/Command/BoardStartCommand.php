<?php

namespace FullVibes\Bundle\BoardBundle\Command;

use Wrep\Daemonizable\Command\EndlessContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use FullVibes\Component\Device\DeviceInterface;
use FullVibes\Component\Actuator\ActuatorInterface;
use FullVibes\Component\Sensor\SensorInterface;
use FullVibes\Bundle\BoardBundle\Entity\Analytics;
use FullVibes\Bundle\BoardBundle\Entity\Board;
use FullVibes\Bundle\BoardBundle\Entity\Device;
use FullVibes\Bundle\BoardBundle\Entity\Actuator;
use FullVibes\Bundle\BoardBundle\Entity\Sensor;
use FullVibes\Component\WiringPi\WiringPi;

class BoardStartCommand extends EndlessContainerAwareCommand {

    const ISO8601 = 'Y-m-d\TH:i:sP';
    
    protected $debug;
    
    protected $devices;
    
    protected function configure() {
        $this->setName('raspiplant:board:start')
                ->setDescription('Board loop start command.')
                ->setHelp("This command allows you to start your board loop...")
                ->setTimeout(10) // Set the timeout in seconds between two calls to the "execute" method
        ;
    }

    // This is a normal Command::initialize() method and it's called exactly once before the first execute call
    protected function initialize(InputInterface $input, OutputInterface $output) {
        
        $this->debug = false;
        $this->tick = 0;
        
        /**
        $airQualityPin = 2;
        $moisturePin1 = 0;
        $moisturePin2 = 1;
        $atomizerPin = 5;
        
        $dhtPin = 6;
        **/
        
        $boards = $this->getBoardManager()->findAll();
        
        if (!$boards)  {
            throw new \Exception("No boards found...");
        }
        
        foreach ($boards as $board) {
            $this->boardInitialize($board, $output);
        }
        
        $output->writeln([
            '=======================================================================',
            '========================   RASPIPLANT START    ========================',
            '=======================================================================',
            php_uname(),
        ]);
    }
    
    protected function boardInitialize(Board $board, $output) {
        
        $output->writeln("Starting Board :" . $board->getName());
        
        $devices = $board->getDevices();
        
        foreach ($devices as $device) {
            $this->devices[$device->getId()] = $this->deviceInitialize($device, $output);
            usleep(100000);
        }
    }
    
    protected function deviceInitialize(Device $device, $output) {
        
        $output->writeln("Starting Device :" . $device->getName());
        
        $fd = WiringPi::wiringPiI2CSetup($device->getAddress());
        $class = $device->getClass();
        $deviceLink = new $class($fd);
        
        $sensors = $device->getSensors();
        foreach ($sensors as $sensor) {
            $this->devices[$device->getId()]['sensors'][] = $this->sensorInitialize($sensor, $deviceLink, $output);
        }
        
        $actuators = $device->getActuators();
        foreach ($actuators as $actuator) {
            $this->devices[$device->getId()]['actuators'][] = $this->actuatorInitialize($actuator, $deviceLink, $output);
        }
    }
    
    protected function actuatorInitialize(Actuator $actuator, $deviceLink, $output) {
        
        $output->writeln("Starting Actuator :" . $actuator->getName());
        
        $class = $actuator->getClass();
        $a = new $class($deviceLink, $actuator->getPin());
        
        if (!($a instanceof ActuatorInterface)) {
            throw new \Exception("Initialization error of actuator: " . $class);
        }
        
        usleep(100000);
        
        return $a;
    }
    
    protected function sensorInitialize(Sensor $sensor, $deviceLink, $output) {
        
        $output->writeln("Starting Sensor :" . $sensor->getName());
        
        $class = $sensor->getClass();
        $s = new $class($deviceLink, $sensor->getPin());
        
        if (!($a instanceof ActuatorInterface)) {
            throw new \Exception("Initialization error of sensor: " . $class);
        }
        
        usleep(100000);
        
        return $s;
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {

        $firedAt = new \DateTime();
        
        $data = array();
        foreach ($this->devices as $id => $device)  {
            $output->writeln("# Starting Read of sensors values at " . $firedAt->format(self::ISO8601));
            $data[$id] = $this->readDeviceSensors($device['sensors']);
            $output->writeln("# Starting set of actuators values at " . $firedAt->format(self::ISO8601));
            $this->setDeviceActuators($device['actuators']);
        }
        
        if (($this->tick % 120) == 0) {    
            foreach ($data as $deviceData) {
                $this->persistValues($deviceData, $firedAt, $output);
            }
        }

        $output->writeln(print_r($data, 1));
        
        $this->tick += 10;
    }
    
    protected function readDeviceSensors(array $sensors) {
        $values = array();
        foreach ($sensors as $sensor) {
            $values[] = json_decode($sensor->readSensorData());
            usleep(100000);
        }
        return $values;
    }
    
    protected function setDeviceActuators(array $actuators) {
        foreach ($actuators as $actuator) {
            //We get the value from database
            
            $actuator->writeSatus();
            usleep(100000);
        }
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
    protected function getBoardManager() {

        return $this->getContainer()->get("board.manager.board_manager");
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
    
    /*
    
     */

}
