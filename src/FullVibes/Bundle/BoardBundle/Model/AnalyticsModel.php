<?php

namespace FullVibes\Bundle\BoardBundle\Model;

class AnalyticsModel
{
    
    /**
     *
     * @var string
     */
    protected $key;
    
    /**
     *
     * @var int
     */
    protected $value;
    
    /**
     *
     * @var string
     */
    protected $firedAt;
    
    /**
     * 
     * @param string $key
     * @param int $value
     */
    public function __construct($key = null, $value = null, $firedAt = null) {
        
        $this->key = $key;
        $this->value = $value;
        
        if (is_null($firedAt)) {
            $this->firedAt = new \DateTime();
        }
    }
    
    /**
     * 
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * 
     * @return scalar
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * 
     * @return \DateTime
     */
    public function getFiredAt() {
        return $this->firedAt;
    }

    /**
     * 
     * @param string $key
     * @return \FullVibes\Bundle\BoardBundle\Model\AnalyticsModel
     */
    public function setKey($key) {
        $this->key = $key;
        return $this;
    }

    /**
     * 
     * @param scalar $value
     * @return \FullVibes\Bundle\BoardBundle\Model\AnalyticsModel
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * 
     * @param \DateTime $firedAt
     * @return \FullVibes\Bundle\BoardBundle\Model\AnalyticsModel
     */
    public function setFiredAt(\DateTime $firedAt) {
        $this->firedAt = $firedAt;
        return $this;
    }


}
