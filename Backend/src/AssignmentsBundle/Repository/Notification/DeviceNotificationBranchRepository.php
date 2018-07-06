<?php

namespace AssignmentsBundle\Repository\Notification;

use ApiBundle\Entity\Branch;

/**
 * DeviceNotificationBranchRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DeviceNotificationBranchRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $branch Branch
     * @param $date_start
     * @param $date_end
     * @return mixed
     */
    public function getNotificationsByBranch($branch, $date_start, $date_end)
    {
        $qb = $this->createQueryBuilder('DeviceNotificationBranch');
        $qb->innerJoin('DeviceNotificationBranch.branch', 'b');
        $qb->andWhere('b = :branch')
            ->andWhere('TIMESTAMPDIFF(second, :date_start, DeviceNotificationBranch.start_time) >= 0')
            ->andWhere('TIMESTAMPDIFF(second, DeviceNotificationBranch.start_time, :date_end) >= 0')
            ->setParameter('branch', $branch)
            ->setParameter('date_start', $date_start)
            ->setParameter('date_end', $date_end);

        $query = $qb->getQuery();
        return $query->getResult();
    }
}