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
        return $this->findBy(array(), array('eventDate' => 'DESC'), 1000);
    }
    
    public function findByKeyAndDate($key, $date)
    {
        $qb = $this->_em->createQueryBuilder();
 
        $qb->select('a')
            ->from('BoardBundle:Analytics', 'a')
            ->where('a.eventDate > :eventDate')
            ->andWhere('a.eventKey = :eventKey')
            ->setParameter('eventDate', $date)
            ->setParameter('eventKey', $key)
            ->orderBy('a.eventDate', 'ASC');

             return $qb->getQuery()->getResult();
    }
}