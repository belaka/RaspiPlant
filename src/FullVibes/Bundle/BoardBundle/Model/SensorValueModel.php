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
    protected $k;
    
    /**
     *
     * @var double
     */
    protected $v;
    
    /**
     *
     * @var \DateTime
     */
    protected $d;

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
        return $this->d->format(DATE_ISO8601) . ':' . $this->k . ':' . $this->v;
    }

    /**
     * @return array
     */
    public static function getSensorValueKeys() {
        return self::SENSOR_VALUE_KEYS;
    }

    /**
     * @return string
     */
    public function getK() {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getV() {
        return $this->slug;
    }

    /**
     * @return string
     */
    public function getD() {
        return $this->class;
    }

    /**
     * @return int|null
     */
    public function getSensor() {
        return $this->sensor;
    }

    /**
     * @param string $k
     * @return $this
     */
    public function setK($k) {
        $this->k = $k;
        return $this;
    }

    /**
     * @param double $v
     * @return $this
     */
    public function setV($v) {
        $this->v = $v;
        return $this;
    }

    /**
     * @param \DateTime|null $d
     * @return $this
     */
    public function setD(\DateTime $d = null) {
        $this->d = $d;
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
