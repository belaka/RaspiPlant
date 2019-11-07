<?php

namespace RaspiPlant\Bundle\BoardBundle\Model;

use RaspiPlant\Component\Model\AbstractModel;
use RaspiPlant\Component\Traits\ActivableTrait;

class CommunicatorModel extends AbstractModel implements ActivableInterface
{
    use ActivableTrait;

    /**
     *
     * @var string
     */
    protected $name;

    /**
     *
     * @var string
     */
    protected $slug;

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
     * @return string
     */
    public function getSlug() {
        return $this->slug;
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
     * @param $slug
     * @return $this
     */
    public function setSlug($slug) {
        $this->slug = $slug;
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
