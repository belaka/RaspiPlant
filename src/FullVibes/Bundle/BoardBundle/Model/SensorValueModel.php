<?php

namespace FullVibes\Bundle\BoardBundle\Model;

use FullVibes\Component\Model\AbstractModel;
use FullVibes\Bundle\BoardBundle\Entity\Sensor;

class SensorValueModel extends AbstractModel
{

    const SENSOR_VALUE_KEYS  = [
        'min',
        'max',
        'night_min',
        'night_max',
        'day_min',
        'day_max'
    ];

    /**
     *
     * @var string
     */
    protected $sensorKey;

    /**
     *
     * @var string
     */
    protected $sensorValueKey;
    
    /**
     *
     * @var double
     */
    protected $sensorValue;
    
    /**
     *
     * @var \DateTime
     */
    protected $sensorDate;

    /**
     * @var Sensor
     */
    protected $sensor;

    /**
     * SensorModel constructor.
     * @param array $data
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
        return $this->sensorValueKey . ':' . $this->sensorDate->format(DATE_ISO8601) . ':' . $this->sensorKey . ':' . $this->sensorValue;
    }

    /**
     * @return array
     */
    public static function getSensorValueKeys() {
        return self::SENSOR_VALUE_KEYS;
    }

    /**
     * @return array
     */
    public static function getSensorValueArray() {
        return array_fill_keys(self::SENSOR_VALUE_KEYS, null);
    }

    /**
     * @return string
     */
    public function getSensorKey() {
        return $this->sensorKey;
    }

    /**
     * @return string
     */
    public function getSensorValueKey() {
        return $this->sensorKey;
    }

    /**
     * @return string
     */
    public function getSensorValue() {
        return $this->sensorValue;
    }

    /**
     * @return string
     */
    public function getSensorDate() {
        return $this->sensorDate;
    }

    /**
     * @return int|null
     */
    public function getSensor() {
        return $this->sensor;
    }

    /**
     * @param string $sensorKey
     * @return $this
     */
    public function setSensorKey($sensorKey) {
        $this->sensorKey = $sensorKey;
        return $this;
    }

    /**
     * @param string $sensorValueKey
     * @return $this
     */
    public function setSensorValueKey($sensorValueKey) {
        $this->sensorValueKey = $sensorValueKey;
        return $this;
    }

    /**
     * @param double $sensorValue
     * @return $this
     */
    public function setSensorValue($sensorValue) {
        $this->sensorValue = $sensorValue;
        return $this;
    }

    /**
     * @param \DateTime|null $sensorDate
     * @return $this
     */
    public function setSensorDate(\DateTime $sensorDate = null) {
        $this->sensorDate = $sensorDate;
        return $this;
    }

    /**
     * @param Sensor $sensor
     * @return $this
     */
    public function setSensor($sensor) {
        $this->sensor = $sensor;
        return $this;
    }

}
