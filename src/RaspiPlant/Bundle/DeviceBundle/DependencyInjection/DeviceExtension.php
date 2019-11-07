<?php

namespace RaspiPlant\Bundle\DeviceBundle\DependencyInjection;

use RaspiPlant\Bundle\DeviceBundle\Provider\DeviceProvider;
use RaspiPlant\Component\Device\Actuator\ActuatorInterface;
use RaspiPlant\Component\Device\Communicator\CommunicatorInterface;
use RaspiPlant\Component\Device\Display\DisplayInterface;
use RaspiPlant\Component\Device\Sensor\SensorInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DeviceExtension extends Extension implements PrependExtensionInterface
{

    const DEVICE_TYPES = [
        'actuators' => ActuatorInterface::class,
        'sensors' => SensorInterface::class,
        'communicators' => CommunicatorInterface::class,
        'displays' => DisplayInterface::class
    ];

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
    }

    public function prepend(ContainerBuilder $container)
    {
        $provider = new DeviceProvider();

        $finder = new Finder();

        // find all files in the current directory
        $finder->files()->in(__DIR__ . '/../../../Component/Device');

        foreach ($finder as $file) {

            if (strpos($file->getFilename(), 'Interface') === false) {
                $reflectedClass = new \ReflectionClass($this->getClassFromFile($file->getRealPath()));
                if (!$reflectedClass->isAbstract()) {
                    foreach (self::DEVICE_TYPES as $deviceType) {
                        if ($reflectedClass->implementsInterface($deviceType)) {
                            $provider->registerDevice(
                                $this->getClassFromFile($file->getRealPath()),
                                $deviceType
                            );
                        }
                    }

                }
            }
        }

        $devices = $provider->all();

        foreach ($devices as $interface => $classes) {
            $key = array_search ($interface, self::DEVICE_TYPES);
            $container->setParameter('device.' . $key, $classes);
        }

    }

    /**
     * @param $classpath
     * @return mixed|string
     */
    private function getClassFromFile($classpath)
    {
        //Grab the contents of the file
        $contents = file_get_contents($classpath);

        //Start with a blank namespace and class
        $namespace = $class = "";

        //Set helper values to know that we have found the namespace/class token and need to collect the string values after them
        $getting_namespace = $getting_class = false;

        //Go through each token and evaluate it as necessary
        foreach (token_get_all($contents) as $token) {

            //If this token is the namespace declaring, then flag that the next tokens will be the namespace name
            if (is_array($token) && $token[0] == T_NAMESPACE) {
                $getting_namespace = true;
            }

            //If this token is the class declaring, then flag that the next tokens will be the class name
            if (is_array($token) && $token[0] == T_CLASS) {
                $getting_class = true;
            }

            //While we're grabbing the namespace name...
            if ($getting_namespace === true) {

                //If the token is a string or the namespace separator...
                if (is_array($token) && in_array($token[0], [T_STRING, T_NS_SEPARATOR])) {

                    //Append the token's value to the name of the namespace
                    $namespace .= $token[1];

                } else if ($token === ';') {

                    //If the token is the semicolon, then we're done with the namespace declaration
                    $getting_namespace = false;

                }
            }

            //While we're grabbing the class name...
            if ($getting_class === true) {

                //If the token is a string, it's the name of the class
                if (is_array($token) && $token[0] == T_STRING) {

                    //Store the token's value as the class name
                    $class = $token[1];

                    //Got what we need, stope here
                    break;
                }
            }
        }

        //Build the fully-qualified class name and return it
        return $namespace ? $namespace . '\\' . $class : $class;

    }
}
