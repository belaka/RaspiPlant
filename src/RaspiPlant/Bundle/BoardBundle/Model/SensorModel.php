<?php

namespace RaspiPlant\Bundle\BoardBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use RaspiPlant\Bundle\BoardBundle\Entity\SensorValue;
use RaspiPlant\Component\Model\AbstractModel;
use RaspiPlant\Component\Traits\ActivableTrait;

class SensorModel extends AbstractModel implements ActivableInterface
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
     * @var string
     */
    protected $class;

    /**
     *
     * @var int
     */
    protected $pin = null;

    /**
     * @var ArrayCollection
     */
    protected $sensorValues;

    /**
     * SensorModel constructor.
     * @param array $data
     * @throws \ReflectionException
     */
    public function __construct($data = array())
    {
        $this->sensorValues = new ArrayCollection();
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
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getClass() {
        return $this->class;
    }

    /**
     * @return int|null
     */
    public function getPin() {
        return $this->pin;
    }

    /**
     * @return ArrayCollection
     */
    public function getSensorValues()
    {
        return $this->sensorValues;
    }

    /**
     * @param $key
     * @return mixed|null
     * @throws \Exception
     */
    public function getSensorValue($key)
    {
        if ($this->sensorValues->containsKey($key)) {
            return $this->sensorValues->get($key);
        }
    }

    /**
     * @param $class
     * @return $this
     */
    public function setClass($class) {
        $this->class = $class;
        return $this;
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
     * @param ArrayCollection $sensorValues
     * @return $this
     */
    public function setSensorValues(ArrayCollection $sensorValues)
    {
        $this->sensorValues = $sensorValues;
        return $this;
    }

    /**
     * @param SensorValue $sensorValue
     * @return $this
     */
    public function addSensorValue(SensorValue $sensorValue)
    {
        $this->sensorValues->add($sensorValue);
        return $this;
    }

}
