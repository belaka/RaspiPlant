<?php

namespace FullVibes\Bundle\BoardBundle\Model;

class AnalyticsModel
{
    
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
     * 
     * @param string $eventKey
     * @param int $eventValue
     * @param \DateTime $eventDate
     */
    public function __construct($eventKey, $eventValue, \DateTime $eventDate) {
        
        $this->eventKey = $eventKey;
        $this->eventValue = $eventValue;
        $this->eventDate = $eventDate;
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
