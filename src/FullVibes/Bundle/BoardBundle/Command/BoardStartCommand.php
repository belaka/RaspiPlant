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
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class BoardStartCommand extends EndlessContainerAwareCommand {

    const ISO8601 = 'Y-m-d\TH:i:sP';

    protected $debug;
    protected $devices = array();
    protected $data = array();
    protected $tick = 0;

    protected function configure() {
        $this->setName('raspiplant:board:start')
                ->setDescription('Board loop start command.')
                ->setHelp("This command allows you to start your board loop...")
                ->setTimeout(10) // Set the timeout in seconds between two calls to the "execute" method
        ;
    }

    // This is a normal Command::initialize() method and it's called exactly once before the first execute call
    protected function initialize(InputInterface $input, OutputInterface $output) {

        //Start alt commands eg:start motors|start motion)
        $command = $this->getApplication()->find('raspiplant:motors:manage');
        $arguments = array(
            'command' => 'raspiplant:motors:manage',
            'action'    => 'start'
        );

        $motorsInput = new ArrayInput($arguments);
        $command->run($motorsInput, $output);

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
            if ($board->isActive()) {
                $this->boardInitialize($board, $output);
            }
        }
        
        $output->writeln("");
    }

    protected function boardInitialize(Board $board, OutputInterface $output) {

        $output->writeln("Starting Board :" . $board->getName());

        $devices = $board->getDevices();

        foreach ($devices as $device) {
            if ($device->isActive()) {
                $this->devices[$device->getId()]['device'] = $this->deviceInitialize($device, $output);
                usleep(100000);
            }
        }
    }

    protected function deviceInitialize(Device $device, OutputInterface $output) {

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
            if ($sensor->isActive()) {
                $this->devices[$device->getId()]['sensors'][$sensor->getId()] = $this->sensorInitialize($sensor, $deviceLink, $output);
            }
        }

        $actuators = $device->getActuators();
        foreach ($actuators as $actuator) {
            if ($actuator->isActive()) {
                $this->devices[$device->getId()]['actuators'][$actuator->getId()] = $this->actuatorInitialize($actuator, $deviceLink, $output);
            }
        }
        
        return $deviceLink;
    }

    protected function actuatorInitialize(Actuator $actuator, $deviceLink, OutputInterface $output) {

        $output->writeln("Starting Actuator :" . $actuator->getName() . " with pin " . $actuator->getPin());

        $class = $actuator->getClass();
        $a = new $class($deviceLink, $actuator->getPin(), $actuator->getName());

        if (!($a instanceof ActuatorInterface)) {
            throw new \Exception("Initialization error of actuator: " . $class);
        }

        usleep(100000);

        return $a;
    }

    protected function sensorInitialize(Sensor $sensor, $deviceLink, OutputInterface $output) {

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

        $output->writeln("#Read from sensors at " . $firedAt->format(self::ISO8601));
        $output->writeln("");
        foreach ($this->devices as $deviceId => $device) {
            if (array_key_exists('sensors', $this->devices[$deviceId]))  {
                $this->readDeviceSensors($this->devices[$deviceId]['sensors'], $output);
            }
        }
        $output->writeln("");

        $output->writeln("#Writing to actuators at " . $firedAt->format(self::ISO8601));
        $output->writeln("");
        foreach ($this->devices as $deviceId => $device) {
            if (array_key_exists('actuators', $this->devices[$deviceId]))  {
                $this->setDeviceActuators($this->devices[$deviceId]['actuators'], $output);
            }
        }
        $output->writeln("");

        if (($this->tick % 120) === 0) {
            $output->writeln("Data added to database " . $firedAt->format(self::ISO8601));
            $output->writeln("");
            foreach ($this->data as $sensorId => $sensorData) {
                $this->persistValues($sensorData, $sensorId, $firedAt);
            }
        }

        if ((($this->tick % 7200) === 0)) {
            //Tak a picture in folder web/motion
            // raspistill -hf -vf -n -w 1024 -h 768 -q 100 -o $(date +"%Y-%m-%d_%H%M").jpg
            $imgPath = $this->getContainer()->getParameter('stills_directory');
            $stillCommand = "raspistill -hf -vf -n -w 1024 -h 768 -q 100 -o " . $imgPath . " $(date +\"%Y-%m-%d_%H%M\").jpg";
            $process = new Process($stillCommand);
            $process->run();

            // executes after the command finishes
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }

        $this->tick += 10;
    }

    protected function readDeviceSensors(array $sensors, OutputInterface $output) {

        foreach ($sensors as $sensorId => $sensor) {

            $this->data[$sensorId] = json_decode($sensor->readSensorData(), true);

            if ($this->data[$sensorId]) {
                if (array_key_exists('error', $this->data[$sensorId]) && !empty($this->data[$sensorId]['error'])) {
                    $output->writeln("Sensor " . $sensor->getName()  . " Error:" . $this->data[$sensorId]['error']);
                } else {
                    unset($this->data[$sensorId]['error']);
                    foreach ($this->data[$sensorId] as $key => $value) {
                        $fields = $sensor::getFields();
                        $output->writeln("Sensor " . $sensor->getName()  . " " . $key . ": " . $value . " " . $fields[$key]['unit']);
                    }
                }
            }
                        
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
            $output->writeln("");
            usleep(100000);
        }
    }

    protected function persistValues($sensorData, $sensorId, $firedAt) {

        $analyticsManager = $this->getAnalyticsManager();
        
        foreach ($sensorData as $key => $value) {
            if ($key !== 'error') {

                $analytics = new Analytics(
                    array(
                        'eventKey' => $sensorId .  '_' . $key,
                        'eventValue' => $value,
                        'eventDate' => $firedAt
                    )
                );
                $analyticsManager->save($analytics);
            }
        }
    }

    /**
     * 
     * @return \FullVibes\Bundle\BoardBundle\Manager\BoardManager
     */
    protected function getBoardManager() {

        return $this->getContainer()->get("board.manager.board_manager");
    }

    /**
     * 
     * @return \FullVibes\Bundle\BoardBundle\Manager\ActuatorManager
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
