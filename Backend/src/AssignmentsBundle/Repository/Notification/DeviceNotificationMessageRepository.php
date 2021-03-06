<?php

namespace AssignmentsBundle\Repository\Notification;

use ApiBundle\Entity\Employee;

/**
 * DeviceNotificationMessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DeviceNotificationMessageRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * @param $employee Employee
     * @param $date_start
     * @param $date_end
     * @return mixed
    */
    public function getMessagesByEmployee($employee, $date_start, $date_end)
    {
        $qb = $this->createQueryBuilder('DeviceNotificationMessage');
        $qb->innerJoin('DeviceNotificationMessage.employee', 'e');
        $qb->andWhere('e = :employee')
            ->andWhere('TIMESTAMPDIFF(second, :date_start, DeviceNotificationMessage.start_time) >= 0')
            ->andWhere('TIMESTAMPDIFF(second, DeviceNotificationMessage.start_time, :date_end) >= 0')
            ->setParameter('employee', $employee)
            ->setParameter('date_start', $date_start)
            ->setParameter('date_end', $date_end);

        $query = $qb->getQuery();
        return $query->getResult();
    }
}
