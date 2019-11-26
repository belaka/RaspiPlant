<?php

namespace RaspiPlant\Bundle\BoardBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use RaspiPlant\Bundle\BoardBundle\Entity\Actuator;
use RaspiPlant\Bundle\BoardBundle\Entity\Communicator;
use RaspiPlant\Bundle\BoardBundle\Entity\Display;
use RaspiPlant\Bundle\BoardBundle\Entity\Sensor;
use RaspiPlant\Bundle\ScriptBundle\Model\ScriptableInterface;
use RaspiPlant\Bundle\ScriptBundle\Traits\ScriptableTrait;
use RaspiPlant\Component\Model\AbstractModel;
use RaspiPlant\Component\Traits\ActivableTrait;
use RaspiPlant\Component\Traits\SluggableTrait;

class BoardModel extends AbstractModel implements ActivableInterface, SluggableInterface, ScriptableInterface
{
    use ActivableTrait, SluggableTrait, ScriptableTrait;

    /** @var string */
    protected $name;

    /** @var double */
    protected $latitude;

    /** @var double */
    protected $longitude;

    /** @var ArrayCollection */
    protected $sensors;

    /** @var ArrayCollection  */
    protected $actuators;

    /** @var ArrayCollection  */
    protected $communicators;

    /** @var ArrayCollection  */
    protected $displays;

    /**
     * BoardModel constructor.
     * @param array $data
     * @throws \ReflectionException
     */
    public function __construct($data = array())
    {
        $this->longitude = null;
        $this->latitude = null;
        $this->sensors = new ArrayCollection();
        $this->actuators = new ArrayCollection();
        $this->communicators = new ArrayCollection();
        $this->displays = new ArrayCollection();
        $this->scripts = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getSensors() {
        return $this->sensors;
    }

    /**
     * @return ArrayCollection
     */
    public function getActuators() {
        return $this->actuators;
    }

    /**
     * @return ArrayCollection
     */
    public function getCommunicators() {
        return $this->communicators;
    }

    /**
     * @return ArrayCollection
     */
    public function getDisplays() {
        return $this->displays;
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
     * @param ArrayCollection $sensors
     * @return $this
     */
    public function setSensors(ArrayCollection $sensors) {
        $this->sensors = $sensors;
        return $this;
    }

    /**
     * @param Sensor $sensor
     */
    public function addSensor(Sensor $sensor)
    {
        $this->sensors[] = $sensor;
    }

    /**
     * @param ArrayCollection $actuators
     * @return $this
     */
    public function setActuators(ArrayCollection $actuators) {
        $this->actuators = $actuators;
        return $this;
    }

    /**
     * @param Actuator $actuator
     */
    public function addActuator(Actuator $actuator)
    {
        $this->actuators[] = $actuator;
    }

    /**
     * @param ArrayCollection $communicators
     * @return $this
     */
    public function setCommunicators(ArrayCollection $communicators) {
        $this->communicators = $communicators;
        return $this;
    }

    /**
     * @param Communicator $communicator
     */
    public function addCommunicator(Communicator $communicator)
    {
        $this->communicators[] = $communicator;
    }

    /**
     * @param ArrayCollection $displays
     * @return $this
     */
    public function setDisplays(ArrayCollection $displays) {
        $this->displays = $displays;
        return $this;
    }

    /**
     * @param Display $display
     */
    public function addDisplay(Display $display)
    {
        $this->displays[] = $display;
    }

    /**
     * @return double|null
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return double|null
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param double $latitude
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @param double $longitude
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }


}
