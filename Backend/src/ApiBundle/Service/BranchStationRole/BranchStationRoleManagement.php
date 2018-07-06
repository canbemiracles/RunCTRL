<?php

namespace ApiBundle\Service\BranchStationRole;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Role\BranchStationOriginRole;
use ApiBundle\Entity\ShiftDay;
use ApiBundle\Repository\BranchShiftRepository;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use ApiBundle\Repository\Report\ProblemReportRepository;
use ApiBundle\Repository\Role\AbstractBranchStationRoleRepository;
use AssignmentsBundle\Entity\Answer\AbstractAnswer;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use AssignmentsBundle\Entity\Assignment\HistoryProblemTask;
use AssignmentsBundle\Repository\Answer\AbstractAnswerRepository;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use AssignmentsBundle\Repository\Assignment\HistoryProblemTaskRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Translation\TranslatorInterface;

class BranchStationRoleManagement
{
    /** @var EntityManager */
    protected $em;

    /** @var integer */
    protected $config_limit_time_open;

    /** @var TranslatorInterface */
    protected $translator;

    public function __construct(EntityManager $em, $config_limit_time_open, TranslatorInterface $translator) {
        $this->em = $em;
        $this->config_limit_time_open = $config_limit_time_open;
        $this->translator = $translator;
    }

    /**
     * Returns filtered list employees.
     * @param $station BranchStation
     * @return mixed
     */
    public function getRoles($station) {
        /** @var $list mixed */
        $list = [];

        /** @var $roles Collection BranchStationRole*/
        $roles = $this->em->getRepository('ApiBundle:Role\BranchStationOriginRole')->findBy(array('branch_station' => $station));

        foreach ($roles as $role) {
            /** @var $record HistoryEmployeeRole*/
            $record = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')->getCurrentHistoryEmployeeRole($role);
            $list[$role->getId()] = array(
                'id' => $role->getId(),
                'name' => $role->getRole(),
                'employee' => is_null($record) ? null : $record->getEmployee()
            );
        }

        return $list;
    }

    /**
     * Returns filtered list roles.
     * @param $station BranchStation
     * @return mixed
     */
    public function getAllRoles($station) {
        /** @var $list mixed */
        $list = [];

        /** @var $repository_roles AbstractBranchStationRoleRepository*/
        $repository_roles = $this->em->getRepository('ApiBundle:Role\AbstractBranchStationRole');

        /** @var $roles Collection AbstractBranchStationRole*/
        $roles = $repository_roles->findBy(array('branch_station' => $station));

        foreach ($roles as $role) {
            /** @var $role AbstractBranchStationRole*/
            /** @var $repository_history_role HistoryEmployeeRoleRepository*/
            $repository_history_role = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');
            /** @var $record HistoryEmployeeRole*/
            $record = $repository_history_role->getCurrentHistoryEmployeeRole($role);
            $list[intval($role->getId())] = array(
                'role' => $role,
                'employee' => is_null($record) ? null : $record->getEmployee()
            );
        }

        return $list;
    }

    /**
     * Returns filtered role.
     * @param $station BranchStation
     * @param $role AbstractBranchStationRole
     * @return mixed
     */
    public function getRoleEmployee($station, $role) {

        /** @var $repository_roles AbstractBranchStationRoleRepository*/
        $repository_roles = $this->em->getRepository('ApiBundle:Role\AbstractBranchStationRole');

        /** @var $role AbstractBranchStationRole*/
        $role = $repository_roles->findOneBy(array('branch_station' => $station, 'id' => $role->getId()));

        /** @var $repository_history_role HistoryEmployeeRoleRepository*/
        $repository_history_role = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');
        /** @var $record HistoryEmployeeRole*/
        $record = $repository_history_role->getCurrentHistoryEmployeeRole($role);

        return array(
            'role' => $role,
            'employee' => is_null($record) ? null : $record->getEmployee()
        );
    }

    /**
     * Returns filtered list employees.
     * @param $station BranchStation
     * @return mixed
     */
    public function getRolesWithShifts($station) {
        /** @var $list mixed */
        $list = [];
        /** @var $repository HistoryEmployeeRoleRepository*/
        $repository = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var $roles Collection BranchStationRole*/
        $roles = $this->em->getRepository('ApiBundle:Role\AbstractBranchStationRole')->findBy(array('branch_station' => $station));
        /** @var $problem_report_repository ProblemReportRepository*/
        $problem_report_repository = $this->em->getRepository('ApiBundle:Report\ProblemReport');

        foreach ($roles as $role) {
            /** @var $record HistoryEmployeeRole*/
            $record = $repository->getCurrentHistoryEmployeeRole($role);
            $list[$role->getId()] = array(
                'id' => $role->getId(),
                'name' => $role->getRole(),
                'color' => $role->getColor(),
                'shift' => $record,
                'done_tasks' => is_null($record) ? null : $this->getDoneTasksByEmployee($record->getEmployee(), $record->getBranchShift()),
                'pending_tasks' => is_null($record) ? null : $this->getPendingTasksByEmployee($record->getEmployee(), $record->getBranchShift()),
                'problem_tasks' => is_null($record) ? null : count($this->getProblemTasksByEmployee($record->getEmployee(), $record->getBranchShift())),
                'problems' => is_null($record) ? null : $this->getProblemTasksByEmployee($record->getEmployee(), $record->getBranchShift()),
                'problem_reports' => $problem_report_repository->getProblemReportByParam($station->getBranch(), array('date' => date('Y-m-d'))),
            );
        }

        return $list;
    }

    public function getDoneTasksByEmployee(Employee $employee, BranchShift $shift)
    {
        /** @var $repository AbstractAnswerRepository*/
        $repository = $this->em->getRepository('AssignmentsBundle:Answer\AbstractAnswer');
        return $repository->getDoneTasksByEmployee($employee, $shift->getTimeOpen());
    }

    public function getPendingTasksByEmployee(Employee $employee, BranchShift $shift)
    {
        /** @var $repository HistoryEmployeeRoleRepository*/
        $repository = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');
        /** @var $repository_assignment AbstractAssignmentRepository */
        $repository_assignment = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');
        /** @var Collection HistoryEmployeeRole $history_employee_roles */
        $history_employee_roles = $repository->getCurrentEmployeeRoles($employee, $shift);
        if($history_employee_roles != null) {
            $count_tasks = 0;
            foreach ($history_employee_roles as $history_employee_role) {
                /** @var $history_employee_role HistoryEmployeeRole*/
                $count_tasks += count($repository_assignment->getPendingTasksByRole($history_employee_role->getRole(), $shift));
            }
            return $count_tasks;
        }
        return 0;
    }

    public function getProblemTasksByEmployee(Employee $employee, BranchShift $shift)
    {
        /** @var HistoryProblemTaskRepository $history_problem_task_repository */
        $history_problem_task_repository = $this->em->getRepository('AssignmentsBundle:Assignment\HistoryProblemTask');

        return $history_problem_task_repository->getProblemsTasksByEmployee($employee, $shift);
    }

    /**
     * Returns tasks by station
     * @param $station BranchStation
     * @param $future bool
     * @return mixed
     */
    public function getTasksByStation($station, $future = false)
    {
        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var $shift_day ShiftDay */
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_not_found");
        }

        /** @var $shift_repository BranchShiftRepository */
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        /** @var $current_shift BranchShift*/
        $current_shift = $shift_repository->getCurrentShift([
            'branch' => $station->getBranch()->getId(),
            'filter_shift_day' => $shift_day->getId(),
            'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
            'filter_time_close' => date('H:i:s')]);

        if(!$current_shift) {
            return $this->translator->trans("shift_management.no_open_shift");
        }

        /** @var $repository AbstractAssignmentRepository*/
        $repository = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        /** @var $history_problem_task_repository HistoryProblemTaskRepository*/
        $history_problem_task_repository = $this->em->getRepository('AssignmentsBundle:Assignment\HistoryProblemTask');

        $fail_tasks = [];
        $history = $history_problem_task_repository->getProblemsTasksByStation($station, $current_shift);
        foreach($history as $problemTask) {
            /** @var $problemTask HistoryProblemTask */
            $fail_tasks[] = $repository->findOneBy(['id' => $problemTask->getAssignment()->getId()]);
        }

        if($future) {
            $response = [
                'pending' => $repository->getPendingTasksByStation($station, $current_shift),
                'done' => $repository->getDoneTasksByStation($station, $current_shift),
                'fail' => $fail_tasks,
                'future' => $repository->getFutureTasksByStation($station, $current_shift)
            ];
        } else {
            $response = [
                'pending' => $repository->getPendingTasksByStation($station, $current_shift),
                'done' => $repository->getDoneTasksByStation($station, $current_shift),
                'fail' => $fail_tasks
            ];
        }

        return $response;
    }

    /**
     * Returns all tasks by station
     * @param $station BranchStation
     * @param $future bool
     * @return mixed
     */
    public function getAllTasksByStation($station, $future = false)
    {
        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var $shift_day ShiftDay */
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_not_found");
        }

        /** @var $shift_repository BranchShiftRepository */
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        $current_shift = $shift_repository->getCurrentShift([
            'branch' => $station->getBranch()->getId(),
            'filter_shift_day' => $shift_day->getId(),
            'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
            'filter_time_close' => date('H:i:s')]);

        if(!$current_shift) {
            return $this->translator->trans("shift_management.no_open_shift");
        }

        /** @var $repository AbstractAssignmentRepository*/
        $repository = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        return $repository->getAllTasksByStation($station, $current_shift, $future);
    }

    /**
     * @param Company $company
     * @param string $term
     * @return array
     */
    public function searchRoles(Company $company, string $term)
    {
        $result = [];
        foreach($company->getBranches() as $branch)
        {
            /** @var Branch $branch*/
            foreach($branch->getStations() as $station)
            {
                /** @var BranchStation $station */
                foreach($station->getRoles() as $role)
                {
                    /** @var BranchStationOriginRole $role */
                    if(strcasecmp($role->getRole(), $term) == 0) {
                        $result[] = $role;
                    }
                }
            }
        }
        return $result;
    }
}