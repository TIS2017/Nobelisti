<?php

namespace AdminBundle\Repository;

/**
 * EventRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function getNearingEvents() {
        $qb = $this->_em->createQueryBuilder('e');
        $qb->select('e')
            ->from('AdminBundle:Event', 'e')
            ->where('DATE_ADD(NOW(), INTERVAL e.notification_threshold DAYS >= e.date_time');

        return $qb->getQuery()->getResult();
    }
}
