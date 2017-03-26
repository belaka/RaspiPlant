<?php

namespace FullVibes\Bundle\BoardBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;

/**
 * Description of SensorValueRepository
 *
 * @author belaka
 */
class SensorValueRepository extends EntityRepository
{
    /**
     * @param $key
     * @return array
     */
    public function findAllWithKey($key)
    {
        return $this->findBy(array('sensorKey' => $key));
    }
}