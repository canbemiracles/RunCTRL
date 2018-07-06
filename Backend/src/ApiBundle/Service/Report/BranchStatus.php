<?php

namespace ApiBundle\Service\Report;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\Report\CashierReportGroup;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\ShiftDay;
use ApiBundle\Repository\BranchShiftRepository;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use ApiBundle\Repository\Report\CashierReportGroupRepository;
use ApiBundle\Repository\Report\CashierReportRepository;
use ApiBundle\Service\BranchShift\BranchShiftManagement;
use ApiBundle\Service\BranchStationRole\BranchStationRoleManagement;
use AssignmentsBundle\Entity\Assignment\HistoryProblemTask;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use AssignmentsBundle\Repository\Assignment\HistoryProblemTaskRepository;
use AssignmentsBundle\Repository\Notification\DeviceNotificationBranchRepository;
use AssignmentsBundle\Repository\Notification\DeviceNotificationMessageRepository;
use AssignmentsBundle\Repository\Notification\DeviceNotificationRoleRepository;
use AssignmentsBundle\Repository\Notification\DeviceNotificationStationRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use ApiBundle\Service\Currency\CurrencyService as CurrencyLayer;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BranchStatus
{
    /** @var EntityManager */
    protected $em;

    /** @var Serializer */
    protected $serializer;

    /** @var CurrencyLayer */
    protected $currency_service;

    /** @var BranchShiftManagement*/
    protected $branchShiftManager;

    /** @var BranchStationRoleManagement*/
    protected $branchStationRoleManagement;

    protected $config_limit_time_open;

    public function __construct(EntityManager $entityManager, Serializer $serializer, CurrencyLayer $currency_service, BranchShiftManagement $branchShiftManager,
                                BranchStationRoleManagement $branchStationRoleManagement, $config_limit_time_open)
    {
        $this->em = $entityManager;
        $this->serializer = $serializer;
        $this->currency_service = $currency_service;
        $this->branchShiftManager = $branchShiftManager;
        $this->branchStationRoleManagement = $branchStationRoleManagement;
        $this->config_limit_time_open = $config_limit_time_open;
    }

    /**
     * Get data from branch.
     * @param Branch $branch
     * @return mixed|string
     */
    public function getLiveData(Branch $branch)
    {
        // Get the current day of the week number
        $day_of_week = date('Y-m-d', strtotime(date('Y-m-d')));

        $expected_workday_start = null;
        $expected_workday_end = null;

        /** @var $repositoryHistory HistoryEmployeeRoleRepository*/
        $repositoryHistory = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var $workday_start HistoryEmployeeRole */
        $workday_start = $repositoryHistory->getOneRecord(array(
            'date_start' => $day_of_week,
            'branch' => $branch,
            'sort_date_start_asc' => true));

        if($workday_start != null) {
            $expected_workday_start = $workday_start->getBranchShift()->getTimeOpen();
            $offset = intval($branch->getCompany()->getTimeZone()->getOffset());
            $time = (new \DateTime())->setTimestamp($workday_start->getDateStart()->getTimestamp());
            $workday_start = $time->modify("{$offset} hour");
        }

        /** @var $workday_end HistoryEmployeeRole */
        $workday_end = $repositoryHistory->getOneRecord(array(
            'date_end' => $day_of_week,
            'branch' => $branch,
            'sort_date_end_desc' => true));

        if($workday_end != null) {
            $expected_workday_end = $workday_end->getBranchShift()->getTimeClose();
        }

        /** @var $assignment_repository AbstractAssignmentRepository */
        $assignment_repository = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        /** @var $assignment_problem_repository HistoryProblemTaskRepository */
        $assignment_problem_repository = $this->em->getRepository(HistoryProblemTask::class);

        /** @var $cashier_reports_repository CashierReportRepository */
        $cashier_reports_repository = $this->em->getRepository('ApiBundle:Report\CashierReport');

        /** @var $repository_history_role HistoryEmployeeRoleRepository */
        $repository_history_role = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var $employees Collection HistoryEmployeeRole*/
        $employees =  $repository_history_role->getStatisticWorking($branch, new \DateTime(date("Y-m-d 00:00:00")));

        $roles_count = 0;

        foreach($branch->getStations() as $station)
        {
            /** @var BranchStation $station */
            $roles_count += $station->getRoles()->count();
        }

        $total_work_time = 0;

        foreach($employees as $key => $employee) {
            /** @var $history HistoryEmployeeRole */
            $history = $employee[0];

            $current_shift = $this->branchShiftManager->getCurrentShiftByStation($history->getRole()->getBranchStation());

            /** @var $active_shifts Collection HistoryEmployeeRole*/
            $current_active_shifts = $repository_history_role->getCurrentEmployeeRoles($history->getEmployee(), $current_shift);

            /** @var  array AbstractAssignment $completed_tasks*/
            $completed_tasks = [];

            /** @var array AbstractAssignment $pending_tasks*/
            $pending_tasks = [];

            /** @var array AbstractAssignment $future_tasks*/
            $future_tasks = [];

            /** @var Collection AbstractAssignment $fail_tasks*/
            $fail_tasks = $this->branchStationRoleManagement->getProblemTasksByEmployee($history->getEmployee(), $current_shift);

            foreach ($current_active_shifts as $item) {
                /** @var $item HistoryEmployeeRole*/
                $completed_tasks = array_merge($completed_tasks, $this->serializer->toArray($assignment_repository->getDoneTasksByRole($item->getRole(), $current_shift)));
                $pending_tasks = array_merge($pending_tasks, $this->serializer->toArray($assignment_repository->getPendingTasksByRole($item->getRole(), $current_shift)));
                $future_tasks = array_merge($fail_tasks, $this->serializer->toArray($assignment_repository->getFutureTasksByRole($item->getRole(), $current_shift)));
            }

            /** @var  Collection AbstractAssignment $all_tasks*/
            $all_tasks = $assignment_repository->getAllTasksByRole($history->getRole(), $current_shift, true);

            $employees[$key] = $this->serializer->toArray(array_shift($employee));

            $employees[$key] = array_merge($employees[$key], array(
                'total_hours' => $employee['total_hours'],
                'total_cost' => $employee['total_cost'],
                'completed_tasks' => $completed_tasks,
                'pending_tasks' => $pending_tasks,
                'fail_tasks' => $fail_tasks,
                'future_tasks' => $future_tasks,
                'all_tasks' => $all_tasks,
            ));

            $total_work_time += floatval($employee['total_hours']);
        }

        /** @var $cashier_report_group_repository CashierReportGroupRepository*/
        $cashier_report_group_repository = $this->em->getRepository('ApiBundle:Report\CashierReportGroup');

        $cashier_reports = [];

        $groups = $cashier_report_group_repository->getGroupReportsByDate(date('Y-m-d'), null, $branch);

        $total_cash = 0;
        foreach($groups as $group) {
            $records = $cashier_report_group_repository->getReportsByGroup($group['group_report'], null, $branch);
            $array = [];
            $total = 0;
            foreach ($records as $record) {
                /** @var $record CashierReportGroup*/
                if($branch->getCompany()->getCurrency() !== $record->getCashierReport()->getCurrency()) {
                    $result = $this->currency_service->convert($record->getCashierReport()->getCurrency()->getCurrency(), $branch->getCompany()->getCurrency()->getCurrency(), $record->getCashierReport()->getAmount());
                    if(!empty($result['result'])) {
                        $total += $result['result'];
                        $array[] = array_merge($this->serializer->toArray($record), array('convert' => array('amount' => $result['result'], 'currency' => $branch->getCompany()->getCurrency())));
                    }
                } else {
                    $array[] = $record;
                    $total += $record->getCashierReport()->getAmount();
                }
            }
            $total_cash += $total;
            $cashier_reports[] = array(
                'reports' => $array,
                'total' => $total
            );
        }

        $employee_budget = $repository_history_role->calculateLiveEmployeeBudgetByBranch($branch)['employee_budget'];

        $ratio = $cashier_reports_repository->calculateRatio($branch->getId(), !empty($employee_budget) ? $employee_budget : 0);

        /** @var $shift_repository BranchShiftRepository */
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        /** @var ShiftDay $shift_day*/
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => date('N', strtotime(date('Y-m-d')))]);

        if($shift_day === null) {
            throw new BadRequestHttpException("Shift day not found");
        }

        /** @var $current_shift BranchShift */
        $current_shift = $shift_repository->getCurrentShift([
            'branch' => $branch->getId(),
            'filter_shift_day' => $shift_day->getId(),
            'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
            'filter_time_close' => date('H:i:s')]);

        if(!empty($current_shift)) {
            /** @var $tasks_finished Collection AbstractAssignment */
            $tasks_finished = $assignment_repository->getDoneTasksByBranch($branch, $current_shift);

            $tasks_future = $assignment_repository->getFutureTasksByBranch($branch, $current_shift);

            /** @var $tasks_finished Collection AbstractAssignment */
            $tasks_pending = $assignment_repository->getPendingTasksByBranch($branch, $current_shift);

            $tasks_problems = $assignment_problem_repository->getProblemsTasksByBranch($branch, $current_shift);

            $shift_date_start = new \DateTime();
            $shift_date_start->setTime("{$current_shift->getTimeOpen()->format('H')}",
                "{$current_shift->getTimeOpen()->format('i')}", "{$current_shift->getTimeOpen()->format('s')}");
            $shift_date_end = new \DateTime();
            $shift_date_end->setTime("{$current_shift->getTimeClose()->format('H')}",
                "{$current_shift->getTimeClose()->format('i')}", "{$current_shift->getTimeClose()->format('s')}");

            if ($current_shift->getTimeOpen() > $current_shift->getTimeClose()) {
                $shift_date_end->modify("+1 day");
            }

            $count_login_employee = count($repository_history_role->getEmployeesWorked(array(
                'date_time_start' => $shift_date_start, 'shift' => $current_shift)));

        } else {
            $tasks_finished = [];
            $tasks_future = [];
            $tasks_pending = [];
            $tasks_problems = [];
            $count_login_employee = 0;
        }

        return array(
            'branch_id' => $branch->getId(),
            'geographical_area' => $branch->getGeographicalArea(),
            'roles_count' =>$roles_count,
            'workday_start' => is_null($workday_start) ? "" : $workday_start,
            'expected_workday_start' => is_null($expected_workday_start) ? "" : $expected_workday_start,
            'branch_manager' => $branch->getBranchManager(),
            'supervisor' => $branch->getSupervisor(),
            'workday_end' => is_null($workday_end) ? "" : $workday_end->getDateEnd(),
            'expected_workday_end' => isset($expected_workday_end) ? "" : $expected_workday_end,
            'total_work_time' => $total_work_time,
            'cashier_reports' => $cashier_reports,
            'commodity_reports' => $this->em->getRepository('ApiBundle:Report\CommodityReport')->getCommodityReportByParam($branch, array('date' => date('Y-m-d'))),
            'problem_reports' => $this->em->getRepository('ApiBundle:Report\ProblemReport')->getProblemReportByParam($branch, array('date' => date('Y-m-d'))),
            'end_of_shifts_reports' => $this->em->getRepository('ApiBundle:Report\EndOfShiftReport')->getEndOfShiftReportByParam(array('created' => date('Y-m-d'), 'branch' => $branch)),
            'commodity_problem_reports' => $this->em->getRepository('ApiBundle:Report\AbstractReport')->getReportsByParam($branch, array(
                'date' => date('Y-m-d'),
                'base_report' => true
            )),
            'employees' => $employees,
            'count_login_employee' => $count_login_employee,
            'ratio' => $ratio,
            'total_cash' => $total_cash,
            'tasks' => array(
                'finished' => $tasks_finished,
                'pending' => $tasks_pending,
                'problems' => $tasks_problems,
                'future' => $tasks_future
            )
        );
    }

    /**
     * @param Branch $branch
     * @param $date_start string
     * @param $date_end string
     * @return mixed
    */
    public function getAllAssignmentAndDeviceNotificationData($date_start, $date_end, Branch $branch)
    {
        /** @var $repository_branch DeviceNotificationBranchRepository */
        $repository_branch = $this->em->getRepository('AssignmentsBundle\Entity\Notification\DeviceNotificationBranch');
        /** @var $repository_message DeviceNotificationMessageRepository */
        $repository_message = $this->em->getRepository('AssignmentsBundle\Entity\Notification\DeviceNotificationMessage');
        /** @var $repository_station DeviceNotificationStationRepository */
        $repository_station = $this->em->getRepository('AssignmentsBundle\Entity\Notification\DeviceNotificationStation');

        $data = [
            'street_address' => !empty($branch->getGeographicalArea()) ? $branch->getGeographicalArea()->getStreetAddress() : '',
            'branch_id' => $branch->getId(),
            'stations' => [],
            'notifications_branch' => $repository_branch->getNotificationsByBranch($branch, $date_start, $date_end),
            'notifications_message' => []
        ];

        foreach ($branch->getEmployees() as $employee) {
            $messages = $repository_message->getMessagesByEmployee($employee, $date_start, $date_end);
            if(!empty($messages)) {
                /** @var $employee Employee */
                $data['notifications_message'][] = [
                    'employee_id' => $employee->getId(),
                    'messages' => $messages
                ];
            }
        }
        $i = 0;
        foreach ($branch->getStations() as $station) {
            /** @var $station BranchStation */
            $notifications_station = $repository_station->getNotificationsByStation($station, $date_start, $date_end);
            $roles = $station->getRoles();
            if(!empty($notifications_station) || !empty($roles)) {
                $data['stations'][$i] = $this->getAllAssignmentAndDeviceNotificationDataByStation($date_start, $date_end, $station);
                $i++;
            }
        }
        return $data;
    }

    /**
     * @param BranchStation $station
     * @param $date_start string
     * @param $date_end string
     * @return mixed
     */
    public function getAllAssignmentAndDeviceNotificationDataByStation($date_start, $date_end, BranchStation $station)
    {
        /** @var $repository_role DeviceNotificationRoleRepository */
        $repository_role = $this->em->getRepository('AssignmentsBundle\Entity\Notification\DeviceNotificationRole');
        /** @var $assignment_repository AbstractAssignmentRepository*/
        $assignment_repository = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');
        /** @var $repository_station DeviceNotificationStationRepository */
        $repository_station = $this->em->getRepository('AssignmentsBundle\Entity\Notification\DeviceNotificationStation');

        $notifications_station = $repository_station->getNotificationsByStation($station, $date_start, $date_end);
        $data = [
            'branch_id' => $station->getBranch()->getId(),
            'station_id' => $station->getId(),
            'roles' => [],
            'notifications_station' => $notifications_station
        ];
        foreach ($station->getRoles() as $role) {
            /** @var $role AbstractBranchStationRole */
            $assignments = $assignment_repository->getTasksByRolePeriod($role, $date_start, $date_end);
            $notifications_role = $repository_role->getNotificationsByRole($role, $date_start, $date_end);
            if(!empty($assignments) || !empty($notifications_role)) {
                $data['roles'][] = [
                    'assignments' => $assignments,
                    'notifications_role' => $notifications_role
                ];
            }
        }
        return $data;
    }
}