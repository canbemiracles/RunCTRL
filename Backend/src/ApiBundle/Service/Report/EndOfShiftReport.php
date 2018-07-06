<?php

namespace ApiBundle\Service\Report;


use ApiBundle\Entity\Report\EndOfShiftReport as Report;
use Doctrine\ORM\EntityManager;

class EndOfShiftReport
{
    /** @var EntityManager */
    protected $em;

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Report generation.
     * @param Report $report
     * @return mixed|string
     */
    public function generateEndOfShiftReport($report) {
        $generate_report = array(
            'work_day_start_time' => $report->getWorkdayStartTime()->format('H:i:s'),
            'work_day_end_time' => is_null($report->getWorkdayEndTime()) ? date('H:i:s') : $report->getWorkdayEndTime()->format('H:i:s')
        );

        if($report->getClosed()) {
            $generate_report['employee_total_work_time'] = $report->getEmployeeTotalWorkTime();
            $generate_report['employee_budget'] = $report->getEmployeeBudget();
            $generate_report['ratio'] = $report->getRatio();
            $generate_report['employees'] = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')
                ->getEmployeesWorked(array(
                    'shift' => $report->getBranchShift(),
                    'date_time_start' => $report->getCreated(),
                    'date_time_end' => $report->getUpdated(),
                ));
            $generate_report['summary_reports'] = $this->em->getRepository('ApiBundle:Report\AbstractReport')->getReportsByShift($report->getBranchShift(), array(
                'base_report' => true,
                'created' => $report->getCreated(),
                'updated' => $report->getUpdated(),
            ));
            $all_tasks = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getListTasksByParam(array(
                'branch' => $report->getBranchShift()->getBranch(),
                'date_time_start' => $report->getCreated(),
                'date_time_end' => $report->getUpdated(),
            ));
            $tasks_completed = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getListTasksByParam(array(
                'branch' => $report->getBranchShift()->getBranch(),
                'date_time_start' => $report->getCreated(),
                'start_time_answer' => $report->getCreated(),
                'date_time_end' => $report->getUpdated(),
                'end_time_answer' => $report->getUpdated(),
            ));
            $generate_report['tasks_status'] = array(
                'count_tasks_completed' => count($tasks_completed),
                'count_tasks_not_completed' => count($all_tasks) - count($tasks_completed),
            );
        } else {
            $total_work_time = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')->dynamicCalculateTotalWorkTime($report->getBranchShift(), array(
                'date_time_start' => $report->getCreated(),
                'date_time_end_current' => date('Y-m-d H:i:s')));
            $employee_budget = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')->dynamicCalculateEmployeeBudget($report->getBranchShift(), array(
                'date_time_start' => $report->getCreated(),
                'date_time_end_current' => date('Y-m-d H:i:s')));
            $ratio = $this->em->getRepository('ApiBundle:Report\CashierReport')->calculateRatio($report->getBranchShift()->getBranch(), !empty($employee_budget[0]) ? $employee_budget[0] : 0);
            $generate_report['employee_total_work_time'] = $total_work_time;
            $generate_report['employee_budget'] = $employee_budget;
            $generate_report['ratio'] = $ratio;
            $generate_report['employees'] = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')
                ->getEmployeesWorked(array(
                    'shift' => $report->getBranchShift(),
                    'date_time_start' => $report->getCreated(),
                    'date_time_end_current' => date('Y-m-d H:i:s'),
                ));
            $generate_report['summary_reports'] = $this->em->getRepository('ApiBundle:Report\AbstractReport')->getReportsByShift($report->getBranchShift(), array(
                'base_report' => true,
                'created' => $report->getCreated(),
                'updated' => date('Y-m-d H:i:s'),
            ));
            $all_tasks = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getListTasksByParam(array(
                'branch' => $report->getBranchShift()->getBranch(),
                'date_time_start' => $report->getCreated(),
                'date_time_end' => date('Y-m-d H:i:s'),
            ));
            $tasks_completed = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getListTasksByParam(array(
                'branch' => $report->getBranchShift()->getBranch(),
                'date_time_start' => $report->getCreated(),
                'start_time_answer' => $report->getCreated(),
                'date_time_end' => $report->getUpdated(),
                'end_time_answer' => date('Y-m-d H:i:s'),
            ));
            $generate_report['tasks_status'] = array(
                'count_tasks_completed' => count($tasks_completed),
                'count_tasks_not_completed' => count($all_tasks) - count($tasks_completed),
            );
        }

        return $generate_report;
    }
}