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
    protected $devices = array();

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
         * */
        $boards = $this->getBoardManager()->findAll();

        if (!$boards) {
            throw new \Exception("No boards found...");
        }
        
        $output->writeln([
            '=======================================================================',
            '========================   RASPIPLANT START    ========================',
            '=======================================================================',
            php_uname(),
        ]);

        $output->writeln("");
        
        foreach ($boards as $board) {
            $this->boardInitialize($board, $output);
        }
        
        $output->writeln("");
    }

    protected function boardInitialize(Board $board, $output) {

        $output->writeln("Starting Board :" . $board->getName());

        $devices = $board->getDevices();

        foreach ($devices as $device) {
            $this->devices[$device->getId()]['device'] = $this->deviceInitialize($device, $output);
            usleep(100000);
        }
    }

    protected function deviceInitialize(Device $device, $output) {

        $output->writeln("Starting Device :" . $device->getName() . " with address " . $device->getAddress());

        //address is a varchar and need hexdec before being sent
        $fd = WiringPi::wiringPiI2CSetup(hexdec($device->getAddress()));
        $class = $device->getClass();
        $deviceLink = new $class($fd);
        
        if (!($deviceLink instanceof DeviceInterface)) {
            throw new \Exception("Initialization error of device: " . $class . " with address " . $device->getAddress());
        }

        $sensors = $device->getSensors();
        foreach ($sensors as $sensor) {
            $this->devices[$device->getId()]['sensors'][$sensor->getId()] = $this->sensorInitialize($sensor, $deviceLink, $output);
        }

        $actuators = $device->getActuators();
        foreach ($actuators as $actuator) {
            $this->devices[$device->getId()]['actuators'][$actuator->getId()] = $this->actuatorInitialize($actuator, $deviceLink, $output);
        }
        
        return $deviceLink;
    }

    protected function actuatorInitialize(Actuator $actuator, $deviceLink, $output) {

        $output->writeln("Starting Actuator :" . $actuator->getName() . " with pin " . $actuator->getPin());

        $class = $actuator->getClass();
        $a = new $class($deviceLink, $actuator->getPin(), $actuator->getName());

        if (!($a instanceof ActuatorInterface)) {
            throw new \Exception("Initialization error of actuator: " . $class);
        }

        usleep(100000);

        return $a;
    }

    protected function sensorInitialize(Sensor $sensor, $deviceLink, $output) {

        $output->writeln("Starting Sensor :" . $sensor->getName() . " with pin " . $sensor->getPin());

        $class = $sensor->getClass();
        $s = new $class($deviceLink, $sensor->getPin(), $sensor->getName());

        if (!($s instanceof SensorInterface)) {
            throw new \Exception("Initialization error of sensor: " . $class);
        }

        usleep(100000);

        return $s;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {

        $firedAt = new \DateTime();

        $this->data = array();

        foreach ($this->devices as $deviceId => $device) {

            $output->writeln("# Starting Read of sensors values at " . $firedAt->format(self::ISO8601));
            $output->writeln("");
            if (array_key_exists('sensors', $this->devices[$deviceId]))  {
                $this->readDeviceSensors($this->devices[$deviceId]['sensors'], $output);
            }

            $output->writeln("# Starting set of actuators values at " . $firedAt->format(self::ISO8601));
            $output->writeln("");
            if (array_key_exists('actuators', $this->devices[$deviceId]))  {
                $this->setDeviceActuators($this->devices[$deviceId]['actuators'], $output);
            }
        }
        
        if (($this->tick % 120) === 0) {
            $output->writeln("Data added to database " . $firedAt->format(self::ISO8601));
            $output->writeln("");
            foreach ($this->data as $sensorId => $sensorData) {
                $this->persistValues($sensorData, $sensorId, $firedAt);
            }
        }

        $this->tick += 10;
    }

    protected function readDeviceSensors(array $sensors, OutputInterface $output) {

        foreach ($sensors as $sensorId => $sensor) {
            
            $this->data[$sensorId] = json_decode($sensor->readSensorData(), true);
            foreach ($this->data[$sensorId] as $key => $value) {
                $output->writeln("Sensor " . $sensor->getName()  . " " . $key . ": " . $value);
            }
            $output->writeln("");
            
            usleep(100000);
        }
    }

    protected function setDeviceActuators(array $actuators, OutputInterface $output) {

        $actuatorManager = $this->getActuatorManager();

        foreach ($actuators as $actuatorId => $actuator) {

            //We get the value from database
            $actuatorData = $actuatorManager->find($actuatorId);
            
            if (!($actuatorData instanceof Actuator)) {
                throw new \Exception("No actuatorData found verify actuator repository for id:" .  $actuatorId);
            }
            

            $actuator->writeStatus($actuatorData->getState());
            
            $output->writeln("Actuator " . $actuator->getName()  . " set to value: " .  $actuatorData->getState());
            usleep(100000);
        }
    }

    protected function persistValues($sensorData, $sensorId, $firedAt) {

        $analyticsManager = $this->getAnalyticsManager();
        
        foreach ($sensorData as $key => $value) {
            if ($key !== 'error') { 
                $analytics = new Analytics($sensorId .  '_' . $key, $value, $firedAt);
                $analyticsManager->save($analytics);
            }
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
    protected function getActuatorManager() {

        return $this->getContainer()->get("board.manager.actuator_manager");
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
        $this->getActuatorManager()->clear();
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
