<?php

namespace ApiBundle\Service\Employee;

use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use AssignmentsBundle\Entity\Answer\AbstractAnswer;
use AssignmentsBundle\Entity\Assignment\HistoryProblemTask;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use Doctrine\ORM\EntityManager;
use Guzzle\Common\Collection;
use JMS\Serializer\Serializer;
use Knp\Component\Pager\Paginator;

/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 22.12.2017
 * Time: 16:48
 */

class EmployeeRoleHistory
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var Serializer  */
    protected $serializer;

    /** @var Paginator  */
    protected $paginator;


    public function __construct(EntityManager $entityManager,  Serializer $serializer, Paginator $paginator)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
        $this->paginator = $paginator;
    }

    public function getCurrentMonthHistory(Employee $employee, $page = 1)
    {
        $em = $this->entityManager;

        /** @var HistoryEmployeeRoleRepository $historyRoleRepository */
        $historyRoleRepository = $em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var AbstractAssignmentRepository $assignmentsRepository */
        $assignmentsRepository = $em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        /** @var array $history HistoryEmployeeRole */
        $history = $historyRoleRepository->getHistoryByCurrentMonth($employee, true);

        /** @var $current_roles Collection HistoryEmployeeRole */
        $current_roles = $historyRoleRepository->getAllCurrentEmployeeRoles($employee);

        $total_pending_tasks = 0;
        $pending_tasks = [];
        $completed_tasks = [];
        $problem_tasks = [];

        if($current_roles != null) {
            foreach ($current_roles as $role) {
                /** @var $role HistoryEmployeeRole*/
                $total_pending_tasks += count($assignmentsRepository->getPendingTasksByRole($role->getRole(), $role->getBranchShift()));
                $pending_tasks = array_merge($pending_tasks, $this->serializer->toArray($assignmentsRepository->getPendingTasksByRole($role->getRole(), $role->getBranchShift())));
            }
        }


        $total_salary = 0;
        $total_work_time = 0;
        $total_shifts = count($history);
        $total_problem_tasks = 0;
        $total_completed_tasks = 0;
        $shifts = [];
        foreach($history as $record)
        {
            /** @var HistoryEmployeeRole $record */
            $work_time = ($record->getDateEnd()->getTimestamp() - $record->getDateStart()->getTimestamp()) / 3600;
            $salary = $work_time * $employee->getHourlyRate();

            $failedTasks = $record->getBranchShift()->getProblemTasks()->count();
            $completedTasks = $record->getBranchShift()->getAssignmentAnswers()->count();

            if(count($problem_tasks) < 10) {
                foreach ($record->getBranchShift()->getProblemTasks() as $problemTask) {
                    /** @var $problemTask HistoryProblemTask */
                    $problem_tasks[] = array('title' => $problemTask->getAssignment()->getTitle(), 'start_time' => $problemTask->getAssignment()->getStartTime());
                }
            }
            if(count($completed_tasks) < 10) {
                foreach ($record->getBranchShift()->getAssignmentAnswers() as $answer) {
                    /** @var $answer AbstractAnswer */
                    $completed_tasks[] = array('title' => $answer->getAssignment()->getTitle(), 'start_time' => $answer->getAssignment()->getStartTime());
                }
            }

            $total_salary += $salary;
            $total_work_time += $work_time;
            $total_problem_tasks += $failedTasks;
            $total_completed_tasks += $completedTasks;
            $shifts[] = [
                'branch_geo' => $record->getBranchShift()->getBranch()->getGeographicalArea(),
                'station' => $record->getRole()->getBranchStation()->getName(),
                'role' => $record->getRole()->getRole(),
                'role_color' => $record->getRole()->getColor(),
                'date_start' => $record->getDateStart(),
                'date_end' => $record->getDateEnd(),
                'total_worked_time' => $work_time,
                'completed_tasks' => $completedTasks,
                'problem_tasks' => $failedTasks,
            ];
        }
        return [
            'total_salary' => $total_salary,
            'total_work_time' => $total_work_time,
            'total_shifts' => $total_shifts,
            'hourly_rate' => $employee->getHourlyRate(),
            'avg_worked_time' => ($total_work_time > 0 && $total_shifts > 0) ? $total_work_time/$total_shifts : 0,
            'total_problem_tasks' => $total_problem_tasks,
            'total_pending_tasks' => $total_pending_tasks,
            'total_done_tasks' => $total_completed_tasks,
            'pending_tasks' => $pending_tasks,
            'problem_tasks' => $problem_tasks,
            'completed_tasks' => $completed_tasks,
            'shifts' => $this->paginator->paginate($shifts, $page, 10)
        ];
    }

    public function getCurrentYearHistory(Employee $employee)
    {
        $current_month = intval(date("m"));
        $records = [];
        for($i = 1; $i <= $current_month; $i++) {
            $records[] = $this->getMonthHistory($employee, $i);
        }
        return $records;
    }

    public function getMonthHistory(Employee $employee, int $month)
    {
        $em = $this->entityManager;

        /** @var HistoryEmployeeRoleRepository $historyRoleRepository */
        $historyRoleRepository = $em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var AbstractAssignmentRepository $assignmentsRepository */
        $assignmentsRepository = $em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        /** @var $history array HistoryEmployeeRole */
        $history = $historyRoleRepository->getHistoryByMonth($employee, $month);

        /** @var $current_roles Collection HistoryEmployeeRole*/
        $current_roles = $historyRoleRepository->getAllCurrentEmployeeRoles($employee);

        $total_pending_tasks = 0;

        if($current_roles != null) {
            foreach ($current_roles as $role) {
                /** @var $role HistoryEmployeeRole*/
                $total_pending_tasks += count($assignmentsRepository->getPendingTasksByRole($role->getRole(), $role->getBranchShift()));
            }
        }


        $total_salary = 0;
        $total_work_time = 0;
        $total_shifts = count($history);
        $total_problem_tasks = 0;
        $total_completed_tasks = 0;
        $shifts = [];
        foreach($history as $record)
        {
            /** @var HistoryEmployeeRole $record */
            $work_time = ($record->getDateEnd()->getTimestamp() - $record->getDateStart()->getTimestamp()) / 3600;
            $salary = $work_time * $employee->getHourlyRate();

            $failedTasks = $record->getBranchShift()->getProblemTasks()->count();
            $completedTasks = $record->getBranchShift()->getAssignmentAnswers()->count();

            $total_salary += $salary;
            $total_work_time += $work_time;
            $total_problem_tasks += $failedTasks;
            $total_completed_tasks += $completedTasks;
            $shifts[] = [
                'branch_geo' => $record->getBranchShift()->getBranch()->getGeographicalArea(),
                'station' => $record->getRole()->getBranchStation()->getName(),
                'role' => $record->getRole()->getRole(),
                'role_color' => $record->getRole()->getColor(),
                'date_start' => $record->getDateStart(),
                'date_end' => $record->getDateEnd(),
                'total_worked_time' => $work_time,
                'completed_tasks' => $completedTasks,
                'problem_tasks' => $failedTasks,
            ];
        }
        return [
            'total_salary' => $total_salary,
            'total_work_time' => $total_work_time,
            'total_shifts' => $total_shifts,
            'hourly_rate' => $employee->getHourlyRate(),
            'avg_worked_time' => ($total_work_time > 0 && $total_shifts > 0) ? $total_work_time/$total_shifts : 0,
            'total_problem_tasks' => $total_problem_tasks,
            'total_pending_tasks' => $total_pending_tasks,
            'total_done_tasks' => $total_completed_tasks,
            'shifts' => $shifts,
        ];
    }
}