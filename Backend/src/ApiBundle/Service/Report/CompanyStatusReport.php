<?php

namespace ApiBundle\Service\Report;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\Report\CashierReportGroup;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use ApiBundle\Repository\Report\AbstractReportRepository;
use ApiBundle\Repository\Report\CashierReportGroupRepository;
use ApiBundle\Repository\Report\CommodityReportRepository;
use ApiBundle\Repository\Report\ProblemReportRepository;
use ApiBundle\Repository\Report\CashierReportRepository;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\DateTime;
use ApiBundle\Service\Currency\CurrencyService as CurrencyLayer;

class CompanyStatusReport
{
    /** @var EntityManager */
    protected $em;

    /** @var BranchStatus */
    protected $branch_status;

    /** @var CurrencyLayer */
    protected $currency_service;

    /** @var Serializer */
    protected $serializer;

    public function __construct(EntityManager $entityManager, BranchStatus $branch_status, CurrencyLayer $currency_service, Serializer $serializer)
    {
        $this->em = $entityManager;
        $this->branch_status = $branch_status;
        $this->currency_service = $currency_service;
        $this->serializer = $serializer;
    }

    /**
     * Get data from company.
     * @param Company $company
     * @return mixed
     */
    public function getLiveData(Company $company)
    {
        // Get the current day of the week number
        $day_of_week = date('Y-m-d', strtotime(date('Y-m-d')));

        /** @var $repository_history_role HistoryEmployeeRoleRepository */
        $repository_history_role = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var $cashier_reports_repository CashierReportRepository */
        $cashier_reports_repository = $this->em->getRepository('ApiBundle:Report\CashierReport');

        /** @var $reports_repository AbstractReportRepository */
        $reports_repository = $this->em->getRepository('ApiBundle:Report\AbstractReport');

        /** @var $assignment_repository AbstractAssignmentRepository */
        $assignment_repository = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        /** @var $workday_start HistoryEmployeeRole */
        $workday_start = $repository_history_role->getOneRecord(array(
            'date_start' => $day_of_week,
            'company' => $company,
            'sort_date_start_asc' => true)
        );

        /** @var $list HistoryEmployeeRole */
        $list = $repository_history_role->getHistoryEmployeeRoleByParam(array(
            'date_start' => $day_of_week,
            'time_close_not_null' => true,
            'company' => $company));

        /** @var $workday_end HistoryEmployeeRole */
        $workday_end = !$list ? $repository_history_role->getOneRecord(array('date_end' => $day_of_week, 'company' => $company, 'sort_date_end_desc' => true)) : date('Y-m-d H:i:s');

        $total_work_time = $repository_history_role->calculateTotalWorkTimeByCompany($company);

        $total_employee_budget = $repository_history_role->dynamicCalculateEmployeeBudgetByCompany($company);

        $ratio = $cashier_reports_repository->calculateRatioByCompany($company, is_null($total_employee_budget) ? 0 : $total_employee_budget['employee_budget']);

        /** @var $all_tasks Collection AbstractAssignment */
        $all_tasks = $assignment_repository->getListTasksByParam(array(
            'company' => $company,
            'date_start' => $day_of_week,
            'date_time_end' => date('Y-m-d H:i:s'),
        ));

        /** @var $tasks_completed Collection AbstractAssignment */
        $tasks_completed = $assignment_repository->getListTasksByParam(array(
            'company' => $company,
            'date_start' => $day_of_week,
            'start_time_answer' => $day_of_week,
            'date_time_end' => date('Y-m-d H:i:s'),
            'end_time_answer' => date('Y-m-d H:i:s'),
        ));

        /** @var $base_reports Collection AbstractReport */
        $base_reports = $reports_repository->getReportsByCompany($company, array(
            'base_report' => true,
            'created' => $day_of_week,
            'updated' => date('Y-m-d H:i:s'),
        ));

        $branches = array();
        foreach ($company->getBranches() as $branch) {
            $branches[] = $this->branch_status->getLiveData($branch);
        }

        if(!is_null($workday_end) && gettype($workday_end) !== "string") {
            $workday_end = $workday_end->getDateEnd();
        }

        return array(
            'workday_start' => is_null($workday_start) ? "" : $workday_start->getDateStart(),
            'workday_end' => is_null($workday_end) ? "" : $workday_end,
            'total_work_time' => $total_work_time,
            'total_employee_budget' => $total_employee_budget,
            'ration' => is_null($ratio) ? null : $ratio['ratio'],
            'count_tasks_completed' => count($tasks_completed),
            'count_tasks_not_completed' => count($all_tasks) - count($tasks_completed),
            'summary_reports' => $base_reports,
            'branches_list' => $branches,
        );
    }

    public function getReportsTodayByStation(Company $company, BranchStation $station)
    {

        /** @var $cashier_report_group_repository CashierReportGroupRepository*/
        $cashier_report_group_repository = $this->em->getRepository('ApiBundle:Report\CashierReportGroup');

        $cashier_reports = [];

        $groups = $cashier_report_group_repository->getGroupReportsByDate(date('Y-m-d'), $station);

        foreach($groups as $group) {
            $records = $cashier_report_group_repository->getReportsByGroup($group['group_report'], $station);
            $array = [];
            $total = 0;
            foreach ($records as &$record) {
                /** @var $record CashierReportGroup*/
                if($company->getCurrency() !== $record->getCashierReport()->getCurrency()) {
                    $result = $this->currency_service->convert($record->getCashierReport()->getCurrency()->getCurrency(), $company->getCurrency()->getCurrency(), $record->getCashierReport()->getAmount());
                    if(!empty($result['result'])) {
                        $total += $result['result'];
                        $array[] = array_merge($this->serializer->toArray($record), array('convert' => array('amount' => $result['result'], 'currency' => $company->getCurrency())));
                    }
                } else {
                    $array[] = $record;
                    $total += $record->getCashierReport()->getAmount();
                }
            }
            $cashier_reports[] = array(
                'reports' => $array,
                'total' => $total
            );
        }

        /** @var $repository_commodity_report CommodityReportRepository*/
        $repository_commodity_report = $this->em->getRepository('ApiBundle:Report\CommodityReport');

        /** @var $repository_problem_report ProblemReportRepository*/
        $repository_problem_report = $this->em->getRepository('ApiBundle:Report\ProblemReport');

        return [
            'company_id' => $company->getId(),
            'cashier_reports' => $cashier_reports,
            'commodity_reports' => $repository_commodity_report->getCommodityReportByParam($station->getBranch(), array(
                'date' => date('Y-m-d'), 'branch_station' => $station)
            ),
            'problem_reports' => $repository_problem_report->getProblemReportByParam($station->getBranch(), array(
                'date' => date('Y-m-d'), 'branch_station' => $station)
            )
        ];
    }

}