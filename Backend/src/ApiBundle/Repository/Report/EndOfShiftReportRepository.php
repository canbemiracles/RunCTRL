<?php

namespace ApiBundle\Repository\Report;

/**
 * EndOfShiftReportRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EndOfShiftReportRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Returns filtered list reports.
     * @param $param
     * @return mixed
     */
    function getEndOfShiftReportByParam($param) {
        $qb = $this->createQueryBuilder('EndOfShiftReport');
        if(!empty($param['branch_shift'])) {
            $qb->andWhere('EndOfShiftReport.branch_shift = :branch_shift')
                ->setParameter('branch_shift', $param['branch_shift']);
        }
        else if (!empty($param['branch']))
        {
            $qb->innerJoin('EndOfShiftReport.branch_shift', 'bs')
                ->innerJoin('bs.branch', 'b');
            $qb->andWhere('b.id = :branch')->setParameter(':branch', $param['branch']);
        }
        if(!empty($param['created'])) {
            $qb->andWhere("DATEDIFF(:created, EndOfShiftReport.created) = 0")
                ->andWhere("TIMESTAMPDIFF(second, TIME(EndOfShiftReport.workday_start_time), TIME( :created )) >= 0")
                ->andWhere("TIMESTAMPDIFF(second, TIME( :created ), TIME(EndOfShiftReport.workday_end_time)) >= 0 or EndOfShiftReport.workday_end_time IS NULL")
                ->setParameter('created', $param['created']);
        }
        if(!empty($param['end_time_null'])) {
            $qb->andWhere("EndOfShiftReport.workday_end_time IS NULL");
        }
        $query = $qb->getQuery();
        return $query->getResult();
    }
}
