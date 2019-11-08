<?php

namespace RaspiPlant\Bundle\BoardBundle\Model;

interface DeviceModelInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return class
     */
    public function getClass();

    /**
     * @return int
     */
    public function getPin();

    /**
     * @param $name
     * @return $this
     */
    public function setName($name);

    /**
     * @param $class
     * @return $this
     */
    public function setClass($class);

    /**
     * @param $pin
     * @return $this
     */
    public function setPin($pin);


}
