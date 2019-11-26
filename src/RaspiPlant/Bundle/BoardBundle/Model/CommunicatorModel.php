<?php

namespace RaspiPlant\Bundle\BoardBundle\Model;

use RaspiPlant\Component\Model\AbstractModel;
use RaspiPlant\Component\Traits\ActivableTrait;
use RaspiPlant\Component\Traits\SluggableTrait;

class CommunicatorModel extends AbstractModel implements DeviceModelInterface, ActivableInterface, SluggableInterface
{
    use ActivableTrait, SluggableTrait;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var class
     */
    protected $class;

    /**
     *
     * @var int
     */
    protected $pin;

    /**
     * CommunicatorModel constructor.
     * @param array $data
     * @throws \ReflectionException
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
    }

    /**
     * @return string
     */
    function __toString()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return class
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @return int
     */
    public function getPin() {
        return $this->pin;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @param $pin
     * @return $this
     */
    public function setPin($pin) {
        $this->pin = $pin;
        return $this;
    }

    /**
     * @param $class
     * @return $this
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
    }

}
