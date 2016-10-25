<?php

namespace FullVibes\Bundle\BoardBundle\Repository\ORM;

use Doctrine\ORM\EntityRepository;

/**
 * Description of AnalyticsRepository
 *
 * @author belaka
 */
class AnalyticsRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->findBy(array(), array('eventDate' => 'ASC'));
    }
}