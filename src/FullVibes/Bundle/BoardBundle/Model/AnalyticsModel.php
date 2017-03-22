<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use FullVibes\Component\Model\AbstractModel;
use FullVibes\Bundle\BoardBundle\Entity\Sensor;
use FullVibes\Component\Sensor\SensorInterface;

class AnalyticsModel extends AbstractModel
{
    /**
     * @var Sensor
     */
    protected $sensor;
    
    /**
     *
     * @var string
     */
    protected $eventKey;
    
    /**
     *
     * @var double
     */
    protected $eventValue;
    
    /**
     *
     * @var string
     */
    protected $eventDate;

    /**
     * AnalyticsModel constructor.
     * @param array $data
     */
    public function __construct($data = array())
    {
        parent::__construct($data);
    }

    /**
     * @return Sensor
     */
    public function getSensor()
    {
        return $this->sensor;
    }

    /**
     * 
     * @return string
     */
    public function getEventKey() {
        return $this->eventKey;
    }

    /**
     * 
     * @return scalar
     */
    public function getEventValue() {
        return $this->eventValue;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getEventDate() {
        return $this->eventDate;
    }

    /**
     * @param SensorInterface $sensor
     * @return $this
     */
    public function setSensor(SensorInterface $sensor)
    {
        $this->sensor = $sensor;
        return $this;
    }

    /**
     * 
     * @param string $eventKey
     * @return \FullVibes\Bundle\BoardBundle\Model\AnalyticsModel
     */
    public function setEventKey($eventKey) {
        $this->eventKey = $eventKey;
        return $this;
    }

    /**
     * 
     * @param scalar $eventValue
     * @return \FullVibes\Bundle\BoardBundle\Model\AnalyticsModel
     */
    public function setEventValue($eventValue) {
        $this->eventValue = $eventValue;
        return $this;
    }

    /**
     * 
     * @param \DateTime $eventDate
     * @return \FullVibes\Bundle\BoardBundle\Model\AnalyticsModel
     */
    public function setEventDate(\DateTime $eventDate) {
        $this->eventDate = $eventDate;
        return $this;
    }


}
