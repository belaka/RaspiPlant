<?php

namespace RaspiPlant\Bundle\BoardBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;

/**
 * Description of SensorRepository
 *
 * @author belaka
 */
class SensorRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAllActive()
    {
        return $this->findBy(array('active' => true));
    }
}
