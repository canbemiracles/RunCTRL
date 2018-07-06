<?php

namespace AssignmentsBundle\Repository\Notification;

use ApiBundle\Entity\Role\AbstractBranchStationRole;

/**
 * DeviceNotificationRoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DeviceNotificationRoleRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $role AbstractBranchStationRole
     * @param $date_start
     * @param $date_end
     * @return mixed
     */
    public function getNotificationsByRole($role, $date_start, $date_end)
    {
        $qb = $this->createQueryBuilder('DeviceNotificationRole');
        $qb->innerJoin('DeviceNotificationRole.role', 'r');
        $qb->andWhere('r = :role')
            ->andWhere('TIMESTAMPDIFF(second, :date_start, DeviceNotificationRole.start_time) >= 0')
            ->andWhere('TIMESTAMPDIFF(second, DeviceNotificationRole.start_time, :date_end) >= 0')
            ->setParameter('role', $role)
            ->setParameter('date_start', $date_start)
            ->setParameter('date_end', $date_end);

        $query = $qb->getQuery();
        return $query->getResult();
    }
}