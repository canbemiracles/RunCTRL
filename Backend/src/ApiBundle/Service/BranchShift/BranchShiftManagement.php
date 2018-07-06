<?php

namespace ApiBundle\Service\BranchShift;

use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\Report\EndOfShiftReport;
use ApiBundle\Entity\Report\ProblemReport;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\Role\BranchStationTempRole;
use ApiBundle\Entity\ShiftDay;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\BranchManager;
use ApiBundle\Entity\User\HistoryBranchManagerWork;
use ApiBundle\Repository\BranchShiftRepository;
use ApiBundle\Repository\CompanyRepository;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use ApiBundle\Repository\Report\EndOfShiftReportRepository;
use ApiBundle\Repository\User\HistoryBranchManagerWorkRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\Branch;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use ApiBundle\Service\Report\ProblemReport as ProblemReportService;
use Symfony\Component\HttpFoundation\Session\Session;

class BranchShiftManagement
{
    /** @var EntityManager */
    protected $em;

    /** @var ProblemReportService */
    protected $problemReport;

    protected $config_limit_time_open;

    /** @var Session */
    protected $session;

    /** @var TranslatorInterface */
    protected $translator;

    protected $limit_roles;

    public function __construct(EntityManager $em, ProblemReportService $problemReport, Session $session,
                                TranslatorInterface $translator, $config) {
        $this->em = $em;
        $this->config_limit_time_open = $config['config_limit_time_open'];
        $this->problemReport = $problemReport;
        $this->session = $session;
        $this->translator = $translator;
        $this->limit_roles = $config['limit_roles'];
    }

    /**
     * Opening a new shift. Return shift or string with error.
     * @return mixed|string
    */
    public function openShift() {
        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var BranchShift */
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_day_not_found");
        }

        /** @var $shift_repository BranchShiftRepository*/
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        /** @var $end_of_shift_repository EndOfShiftReportRepository*/
        $end_of_shift_repository = $this->em->getRepository('ApiBundle:Report\EndOfShiftReport');

        /** @var $problem_report_repository \ApiBundle\Repository\Report\ProblemReportRepository*/
        $problem_report_repository = $this->em->getRepository('ApiBundle:Report\ProblemReport');

        /** @var $history_role_repository HistoryEmployeeRoleRepository*/
        $history_role_repository = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var $shifts Collection BranchShift */
        $shifts = $shift_repository->getBranchShiftsByParam(
            array(
                'filter_day' => $shift_day->getId(), 'filter_time_open' => date('H:i:s'),
                'filter_limit_open_time' => $this->config_limit_time_open
            )
        );

        if($shifts === null) {
            return $this->translator->trans("shift_management.shifts_not_found");
        }
        $close_shift_employee = [];
        foreach ($shifts as $shift) {
            $prev_shift = $shift_repository->prevShift($shift->getBranch(), $day_of_week, date('H:i:s'));

            if($prev_shift) {
                /** @var $prev_end_of_shift_report Collection EndOfShiftReport */
                $prev_end_of_shift_report = $this->em->getRepository('ApiBundle:Report\EndOfShiftReport')->findBy(['branch_shift' => $prev_shift->getId()], ['id' => 'DESC']);
            } else {
                $prev_end_of_shift_report = null;
            }
            if(!empty($prev_end_of_shift_report) && !$prev_end_of_shift_report[0]->getClosed()) {
                $close_shift_employee = $this->closeOneShift($prev_shift, $prev_end_of_shift_report[0]);
                $this->createEndOfShiftReport($shift);
            } else {
                $end_of_shift_report_current = $end_of_shift_repository->getEndOfShiftReportByParam(['branch_shift' => $shift->getId(), 'created' => date('Y-m-d H:i:s')]);
                if(!$end_of_shift_report_current) {
                    $history_employee_role = $history_role_repository->getHistoryEmployeeRoleByParam(
                        array(
                            'shift' => $shift->getId(), 'date_start' => date('Y-m-d H:i:s'),
                            'time_open' => date('H:i:s', strtotime("-{$this->config_limit_time_open} minutes", strtotime($shift->getTimeOpen()->format('H:i:s')))), 'time_close_null' => true)
                    );
                    if($history_employee_role) {
                        $this->createEndOfShiftReport($shift);
                    } elseif(!$history_employee_role) {
                        $stations = $shift->getBranch()->getStations();
                        foreach ($stations as $station) {
                            /** @var $station BranchStation */
                            $exist_reports = $problem_report_repository->getProblemReportByParam($station->getBranch(), array(
                                    'date' => date('Y-m-d'), 'branch_station' => $station, 'branch_shift' => $shift)
                            );
                            if(!empty($exist_reports)) {
                                continue;
                            }
                            $this->problemReport->generateProblemReport(
                                $station,
                                $shift,
                                "Shift №{$shift->getId()} did not open. (Station №{$station->getId()})",
                                'None of the employees entered the system at the beginning of the shift');
                        }
                    }
                }
            }
        }

        return array(
            'close_shift_employee' => $close_shift_employee,
            'open_shifts' => $shifts
        );
    }

    /**
     * Closing shifts. Return shift or string with error.
     * @return mixed|string
     */
    public function closeShift() {
        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var $shift_day ShiftDay */
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_day_not_found");
        }

        /** @var $shift_repository BranchShiftRepository*/
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        /** @var $end_of_shift_repository EndOfShiftReportRepository*/
        $end_of_shift_repository = $this->em->getRepository('ApiBundle:Report\EndOfShiftReport');

        /** @var $problem_report_repository \ApiBundle\Repository\Report\ProblemReportRepository*/
        $problem_report_repository = $this->em->getRepository('ApiBundle:Report\ProblemReport');

        /** @var $history_role_repository HistoryEmployeeRoleRepository*/
        $history_role_repository = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var $shifts Collection BranchShift */
        $shifts = $shift_repository->getBranchShiftsByParam(array('filter_day' => $shift_day->getId()));

        if($shifts === null) {
            return $this->translator->trans("shift_management.shifts_not_found");
        }
        // List of employees who Forcibly closed shift
        $close_shift_employee = [];

        foreach ($shifts as $shift) {
            // Get a list of working employees
            $history_employee_role = $history_role_repository
                ->getHistoryEmployeeRoleByParam(array('shift' => $shift->getId(), 'date_start' => date('Y-m-d H:i:s'),
                    'time_open' => date('H:i:s', strtotime("-{$this->config_limit_time_open} minutes", strtotime($shift->getTimeOpen()->format('H:i:s')))), 'time_close_not_null' => true));
            // Get a list of employees who finished working
            $count_history_employee_role = count($history_role_repository
                ->getHistoryEmployeeRoleByParam(array('shift' => $shift->getId(), 'date_start' => date('Y-m-d H:i:s'),
                    'time_open' => date('H:i:s', strtotime("-{$this->config_limit_time_open} minutes", strtotime($shift->getTimeOpen()->format('H:i:s')))))));
            // Get the end_of_shift report of current shift
            $end_of_shift_report_current = $end_of_shift_repository
                ->getEndOfShiftReportByParam(['branch_shift' => $shift->getId(), 'created' => date('Y-m-d H:i:s'), 'end_time_null' => true]);
            if($count_history_employee_role == count($history_employee_role) && $end_of_shift_report_current
                && date('Y-m-d H:i:s') >= date("Y-m-d {$shift->getTimeClose()->format('H:i:s')}")) {
                $end_of_shift_report_current = $end_of_shift_report_current[0];
                $this->closeOneShift($shift, $end_of_shift_report_current, false);
            } elseif($end_of_shift_report_current) {
                $start_next_shift = $shift_repository->nextShiftBegin($shift->getBranch(), [
                        'filter_time' => date('H:i:s'),
                        'filter_day' => $shift_day->getDay(),
                        'filter_limit_time' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s'))))]);
                if ($count_history_employee_role != count($history_employee_role) && $start_next_shift) {
                    $close_shift_employee = $this->closeOneShift($shift, $end_of_shift_report_current[0]);
                } elseif ($count_history_employee_role == count($history_employee_role)
                    && date('Y-m-d H:i:s') < date("Y-m-d {$shift->getTimeClose()->format('H:i:s')}")) {
                    $stations = $shift->getBranch()->getStations();
                    foreach ($stations as $station) {
                        /** @var $station BranchStation */
                        $exist_reports = $problem_report_repository->getProblemReportByParam($station->getBranch(), array(
                                'date' => date('Y-m-d'), 'branch_station' => $station, 'branch_shift' => $shift)
                        );
                        if(!empty($exist_reports)) {
                            continue;
                        }
                        $this->problemReport->generateProblemReport(
                            $station,
                            $shift,
                            "Shift №{$shift->getId()} did not close (Station №{$station->getId()})",
                            'All employees logout from the system at the closing of the shift');
                    }
                }
            }
        }

        return array(
            'close_shift_employee' => $close_shift_employee,
            'close_shifts' => $shifts
        );
    }


    /**
     * Opening a new shift by employee. Return shift or string with error.
     * @param $user AbstractUser
     * @param $branch_station_id
     * @param $employee_id
     * @param $role_id
     * @return mixed|string
     */
    public function openShiftByEmployee($user, $branch_station_id, $employee_id, $role_id) {

        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var $shift_day ShiftDay */
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_day_not_found"));
        }

        /** @var $branch_station BranchStation */
        $branch_station = $this->em->getRepository('ApiBundle:BranchStation')->findOneBy(['id' => $branch_station_id]);

        if($branch_station === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.station_not_found"));
        }

        if(!$user->hasRole('ROLE_OWNER') && !$user->hasRole('ROLE_DEVICE') && !$branch_station->getBranch()->getCompany()->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->translator->trans("shift_management.not_allowed_open_shift"));
        }

        /** @var  $employee Employee */
        $employee = $this->em->getRepository('ApiBundle:Employee')->findOneBy(['id' => $employee_id]);

        if($employee === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_found"));
        }

        /** @var $role AbstractBranchStationRole */
        $role = $this->em->getRepository('ApiBundle:Role\AbstractBranchStationRole')->findOneBy(['id' => $role_id]);

        if($role === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.role_not_found"));
        }

        if(!$branch_station->getRoles()->contains($role)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.role_not_tied"));
        }

        if(!$branch_station->getBranch()->getEmployees()->contains($employee) && $branch_station->getOriginRoles()->contains($role)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_tied"));
        }

        if(!$branch_station->getBranch()->getCompany()->getEmployees()->contains($employee) && $branch_station->getTempRoles()->contains($role)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_tied_company"));
        }

        /** @var $shift_repository BranchShiftRepository */
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        /** @var $current_shift BranchShift */
        $current_shift = $shift_repository->getCurrentShift([
            'branch' => $branch_station->getBranch()->getId(),
            'filter_shift_day' => $shift_day->getId(),
            'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
            'filter_time_close' => date('H:i:s')]);

        if(!$current_shift) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.login_failed"));
        }

        if(!$employee->getBranchShifts()->contains($current_shift)) {
            //throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_tied_shift"));
        }

        /** @var $repository_history_role HistoryEmployeeRoleRepository */
        $repository_history_role = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var  Collection HistoryEmployeeRole $history_employee*/
        $histories_employee = $repository_history_role->getHistoriesActiveByEmployee($employee);

        if(!empty($histories_employee) && count($histories_employee) == $this->limit_roles) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.already_authorized"));
        }

        /** @var  HistoryEmployeeRole $history_role*/
        $history_role = $repository_history_role->getCurrentHistoryEmployeeRole($role);

        if(!empty($history_role)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.already_taken_role"));
        }

        $history_employee_role = new HistoryEmployeeRole();
        $history_employee_role->setEmployee($employee);
        $history_employee_role->setRole($role);
        $history_employee_role->setBranchShift($current_shift);
        $history_employee_role->setDateStart(new \DateTime(date('Y-m-d H:i:s')));
        $this->em->persist($history_employee_role);
        $this->em->flush();

        $this->session->set('time_zone', $branch_station->getBranch()->getCompany()->getTimeZone());

        /** @var $not_completed_tasks Collection AbstractAssignment*/
        $not_completed_tasks = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getListTasksByParam(array(
            'role' => $history_employee_role->getRole(),
            'date_start' => date('Y-m-d'),
            'not_answer' => true
        ));

        $massage = '';
        if(!empty($not_completed_tasks)) {
            $massage  = 'The previous employee did not complete the tasks ';
            foreach ($not_completed_tasks as $task) {
                $massage .= " №{$task->getId()}";
            }
        }

        return array('employee' => $employee, 'shift' => $current_shift, 'message' => $massage);
    }


    /**
     * Accept or not accept tasks of previous employee
     * @param $employee_id
     * @param $role_id
     * @param $accept
     * @return mixed|string
     */
    public function accept_open_assignment($employee_id, $role_id, $accept)
    {
        /** @var  Employee */
        $employee = $this->em->getRepository('ApiBundle:Employee')->findOneBy(['id' => $employee_id]);

        if($employee === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_found"));
        }

        /** @var $role AbstractBranchStationRole */
        $role = $this->em->getRepository('ApiBundle:Role\AbstractBranchStationRole')->findOneBy(['id' => $role_id]);

        if($role === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.role_not_found"));
        }

        /** @var $not_completed_tasks Collection AbstractAssignment*/
        $not_completed_tasks = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getListTasksByParam(array(
            'role' => $role_id,
            'date_start' => date('Y-m-d'),
            'not_answer' => true
        ));

        /** @var  HistoryEmployeeRole $history_employee*/
        $history_employee = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')->getOneRecord(array(
            'not_employee' => $employee,
            'role' => $role_id,
            'end_time_null' => true,
            'sort_date_start_desc' => true
        ));

        if($history_employee === null) {
            return $this->translator->trans("shift_management.employee_not_found_in_history");
        }

        if (boolval($accept)) {
            if(!empty($not_completed_tasks)) {
                foreach ($not_completed_tasks as $task) {
                    $task->setSnoozeCount(0);
                    $this->em->persist($task);
                }
            }
            if($history_employee->getDateEnd() !== null) {
                $history_employee->setDateEnd(new \DateTime());
                $this->em->persist($history_employee);
            }
            $this->em->flush();
            return $this->translator->trans("shift_management.accepted_assignments");
        } else {
            $temp_role = new BranchStationTempRole();
            $temp_role->setRole($role->getRole());
            $temp_role->setBranchStation($role->getBranchStation());
            $temp_role->setOriginRole($role);
            $this->em->persist($temp_role);
            $this->em->flush();

            $history_employee->setRole($temp_role);
            $this->em->persist($history_employee);

            if(!empty($not_completed_tasks)) {
                foreach ($not_completed_tasks as $task) {
                    $task->setRole($temp_role);
                    $this->em->persist($task);
                }
            }

            $this->em->flush();

            $this->problemReport->generateProblemReport(
                $role->getBranchStation(),
                $history_employee->getBranchShift(),
                "Not accept the assignments",
                'The Employee not accepted the assignments of the previous employee');
            return $this->translator->trans("shift_management.platform_notify");
        }

    }


    /**
     * Closing a shift by employee. Return employee or string with error.
     * @param $employee_id
     * @param $shift_id
     * @param $branch_id
     * @return mixed|string
     */
    public function logoutShiftByEmployee($employee_id, $shift_id, $branch_id, $branch_station_id, $role_id)
    {
        /** @var Branch $branch*/
        $branch = $this->em->getRepository('ApiBundle:Branch')->findOneBy(['id' => $branch_id]);

        if($branch === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.branch_not_found"));
        }

        /** @var $branch_station BranchStation */
        $branch_station = $this->em->getRepository('ApiBundle:BranchStation')->findOneBy(['id' => $branch_station_id]);

        if($branch_station === null || !$branch->getStations()->contains($branch_station)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.station_not_found"));
        }

        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var ShiftDay $shift_day*/
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_day_not_found"));
        }

        /** @var BranchShift */
        $shift = $this->em->getRepository('ApiBundle:BranchShift')->findOneBy(['id' => $shift_id]);

        if($shift === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_not_found"));
        }

        if(!$branch->getShifts()->contains($shift)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_not_tied_branch"));
        }

        if(!$shift_day->getBranchShifts()->contains($shift)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_not_tied_day"));
        }

        /** @var  Employee $employee*/
        $employee = $this->em->getRepository('ApiBundle:Employee')->findOneBy(['id' => $employee_id]);

        if($employee === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_found"));
        }

        /** @var $role AbstractBranchStationRole */
        $role = $this->em->getRepository('ApiBundle:Role\AbstractBranchStationRole')->findOneBy(['id' => $role_id]);

        if($role === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.role_not_found"));
        }

        if(!$branch_station->getRoles()->contains($role)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.role_not_tied"));
        }

        if(!$branch->getEmployees()->contains($employee) && $branch_station->getOriginRoles()->contains($role)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_tied_branch"));
        }

        if(!$branch->getCompany()->getEmployees()->contains($employee) && $branch_station->getTempRoles()->contains($role)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_tied_company"));
        }

        /** @var $repository_history_role HistoryEmployeeRoleRepository */
        $repository_history_role = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var  HistoryEmployeeRole $history_employee*/
        $history_employee = $repository_history_role->getCurrentHistoryEmployeeRole($role);

        if($history_employee === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_found_in_history"));
        }

        /** @var  Collection AbstractAssignment $completed_tasks*/
        $not_completed_tasks = $this->em->getRepository('AssignmentsBundle:Assignment\AbstractAssignment')->getListTasksByParam(array(
            'role' => $history_employee->getRole(),
            'date_start' => date('Y-m-d'),
            'not_answer' => true
        ));

        if(!empty($not_completed_tasks)) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.has_not_completed_tasks"));
        }

        $history_employee->setDateEnd(new \DateTime());
        $this->em->persist($history_employee);
        $this->em->flush();

        return $history_employee;
    }

    /**
     * Opening a new shift by manager. Return shift or string with error.
     * @param $user BranchManager
     * @param $branch_station_id
     * @return mixed|string
     */
    public function openShiftByBranchManager($user, $branch_station_id) {

        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var $shift_day ShiftDay */
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_day_not_found"));
        }

        /** @var $branch_station BranchStation */
        $branch_station = $this->em->getRepository('ApiBundle:BranchStation')->findOneBy(['id' => $branch_station_id]);

        if($branch_station === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.station_not_found"));
        }

        if(!$user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) || $user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) && $branch_station->getBranch()->getBranchManager() !== $user) {
            throw new AccessDeniedHttpException($this->translator->trans("shift_management.not_allowed_open_shift"));
        }

        /** @var $branch_shift_repository BranchShiftRepository*/
        $branch_shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        /** @var $current_shift BranchShift */
        $current_shift = $branch_shift_repository
            ->getCurrentShift([
                'branch' => $branch_station->getBranch()->getId(),
                'filter_shift_day' => $shift_day->getId(),
                'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
                'filter_time_close' => date('H:i:s')]);

        if(!$current_shift) {
            return $this->translator->trans("shift_management.login_failed");
        }

        /** @var $repository_history_work HistoryBranchManagerWorkRepository */
        $repository_history_work = $this->em->getRepository('ApiBundle:User\HistoryBranchManagerWork');

        /** @var  HistoryBranchManagerWork $history_manager*/
        $history_manager = $repository_history_work->getCurrentRecordManager($user);

        if(!empty($history_manager)) {
            return $this->translator->trans("shift_management.already_authorized");
        }

        $history_work = new HistoryBranchManagerWork();
        $history_work->setBranchManager($user);
        $history_work->setBranchShift($current_shift);
        $history_work->setDateStart(new \DateTime(date('Y-m-d H:i:s')));
        $this->em->persist($history_work);
        $this->em->flush();

        $this->session->set('time_zone', $branch_station->getBranch()->getCompany()->getTimeZone());

        return array('manager' => $history_work, 'shift' => $current_shift);
    }

    /**
     * Closing a shift by manager. Return employee or string with error.
     * @param $user BranchManager
     * @param $shift_id
     * @param $branch_id
     * @return mixed|string
     */
    public function logoutShiftByBranchManager($user, $shift_id, $branch_id)
    {

        /** @var Branch $branch*/
        $branch = $this->em->getRepository('ApiBundle:Branch')->findOneBy(['id' => $branch_id]);

        if($branch === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.branch_not_found"));
        }

        if(!$user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) || $user->hasRole(AbstractUser::ROLE_BRANCH_MANAGER) && $branch->getBranchManager() !== $user) {
            throw new AccessDeniedHttpException($this->translator->trans("shift_management.not_allowed_close_shift"));
        }

        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var ShiftDay $shift_day*/
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_day_not_found"));
        }

        /** @var BranchShift */
        $shift = $this->em->getRepository('ApiBundle:BranchShift')->findOneBy(['id' => $shift_id]);

        if($shift === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.shift_not_found"));
        }

        if(!$branch->getShifts()->contains($shift)) {
            return $this->translator->trans("shift_management.shift_not_tied_branch");
        }

        if(!$shift_day->getBranchShifts()->contains($shift)) {
            return $this->translator->trans("shift_management.shift_not_tied_day");
        }

        /** @var $repository_history_work HistoryBranchManagerWorkRepository */
        $repository_history_work = $this->em->getRepository('ApiBundle:User\HistoryBranchManagerWork');

        /** @var  HistoryBranchManagerWork $history_manager*/
        $history_manager = $repository_history_work->getCurrentRecordManager($user);

        if($history_manager === null) {
            throw new BadRequestHttpException($this->translator->trans("shift_management.employee_not_found_in_history"));
        }

        $history_manager->setDateEnd(new \DateTime());
        $this->em->persist($history_manager);
        $this->em->flush();

        return $history_manager;
    }

    /**
     * Closing shift of branch managers
    */
    public function closeOldShiftBranchManagers()
    {
        /** @var $repository_history_work HistoryBranchManagerWorkRepository */
        $repository_history_work = $this->em->getRepository('ApiBundle:User\HistoryBranchManagerWork');

        /** @var $shift_repository BranchShiftRepository*/
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        $now = new \DateTime();

        // Get the current day of the week number
        $day_of_week = $now->format('N');

        /** @var ShiftDay $shift_day*/
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_day_not_found");
        }

        /** @var $activeRecords Collection HistoryBranchManagerWork*/
        $activeRecords = $repository_history_work->getOpenShifts();

        $close_shift_manager = [];

        foreach ($activeRecords as $record) {
            /** @var $record HistoryBranchManagerWork*/

            /** @var $current_shift BranchShift*/
            $current_shift = $shift_repository->getCurrentShift([
                'branch' => $record->getBranchManager()->getBranchId(),
                'filter_shift_day' => $shift_day->getId(),
                'filter_time_open' => (new \DateTime())->modify("+{$this->config_limit_time_open} minutes")->format('H:i:s'),
                'filter_time_close' => $now->format('H:i:s')]);

            if($record->getBranchShift() !== $current_shift || $record->getDateStart()->format('Y-m-d') !== $now->format('Y-m-d')) {
                $record->setDateEnd($now);
                $this->em->persist($record);
                $close_shift_manager[] = $record;
            }
        }
        $this->em->flush();

        return $close_shift_manager;
    }

    /**
     * Closing shift of employees
    */
    public function closeOldShiftEmployees()
    {
        /** @var $shift_repository BranchShiftRepository*/
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        /** @var $repository_history_role HistoryEmployeeRoleRepository */
        $repository_history_role = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');

        $now = new \DateTime();

        // Get the current day of the week number
        $day_of_week = $now->format('N');

        /** @var ShiftDay $shift_day*/
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_day_not_found");
        }

        /** @var $activeRecords Collection HistoryEmployeeRole*/
        $activeRecords = $repository_history_role->getOpenShifts();

        $close_shift_employees = [];

        foreach ($activeRecords as $record) {
            /** @var $record HistoryEmployeeRole*/

            /** @var $current_shift BranchShift*/
            $current_shift = $shift_repository->getCurrentShift([
                'branch' => $record->getBranchShift()->getBranchId(),
                'filter_shift_day' => $shift_day->getId(),
                'filter_time_open' => (new \DateTime())->modify("+{$this->config_limit_time_open} minutes")->format('H:i:s'),
                'filter_time_close' => $now->format('H:i:s')]);

            if($record->getBranchShift() !== $current_shift || $record->getDateStart()->format('Y-m-d') !== $now->format('Y-m-d')) {
                $record->setDateEnd($now);
                $this->em->persist($record);
                $close_shift_employees[] = $record;
            }
        }
        $this->em->flush();

        return $close_shift_employees;
    }


    /**
     * Return current shift by employee
     * @param $branch_station
     * @return mixed|string
     */
    public function getCurrentShiftByStation($branch_station)
    {
        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var ShiftDay $shift_day*/
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_day_not_found");
        }

        /** @var BranchStation $station*/
        $station = $this->em->getRepository('ApiBundle:BranchStation')->findOneBy(['id' => $branch_station]);

        if($station === null) {
            return $this->translator->trans("shift_management.station_not_found");
        }

        /** @var $shift_repository BranchShiftRepository*/
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');

        return $shift_repository->getCurrentShift([
                'branch' => $station->getBranch()->getId(),
                'filter_shift_day' => $shift_day->getId(),
                'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
                'filter_time_close' => date('H:i:s')]);
    }

    /**
     * @param $company Company
     * @return mixed
    */
    public function getEmployeesCurrentShiftByCompany(Company $company)
    {
        $data = [];
        if(!empty($company->getBranches())) {
            foreach ($company->getBranches() as $branch) {
                /** @var $branch Branch */
                $data = array_merge($data, $this->getEmployeesCurrentShiftByBranch($branch));

            }
        }
        return $data;
    }

    /**
     * @param Branch $branch
     * @return mixed
     */
    public function getEmployeesCurrentShiftByBranch(Branch $branch) {
        $data = [];
        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));
        /** @var ShiftDay $shift_day*/
        $shift_day = $this->em->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);
        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_day_not_found");
        }
        /** @var $shift_repository BranchShiftRepository */
        $shift_repository = $this->em->getRepository('ApiBundle:BranchShift');
        /** @var $history_role_repository HistoryEmployeeRoleRepository */
        $history_role_repository = $this->em->getRepository('ApiBundle:HistoryEmployeeRole');
        /** @var $current_shift BranchShift */
        $current_shift = $shift_repository
            ->getCurrentShift([
                'branch' => $branch->getId(),
                'filter_shift_day' => $shift_day->getId(),
                'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
                'filter_time_close' => date('H:i:s')]);
        if (!empty($current_shift) && !empty($current_shift->getEmployees())) {
            foreach ($current_shift->getEmployees() as $employee) {
                /** @var $employee Employee */
                /** @var $records Collection HistoryEmployeeRole */
                $records = $history_role_repository->getCurrentEmployeeRoles($employee, $current_shift);
                if(!empty($records)) {
                    $roles = array();
                    foreach ($records as $record) {
                        /** @var $record HistoryEmployeeRole*/
                        $roles[] = $record->getRole();
                    }
                    $data[] = array(
                        'id' => $employee->getId(),
                        'first_name' => $employee->getFirstName(),
                        'last_name' => $employee->getLastName(),
                        'avatar' => $employee->getAvatar(),
                        'geo' => $employee->getGeographicalArea(),
                        'roles' => $roles,
                        'branch' => current($roles)->getBranchId()
                    );
                }
            }
        }
        return $data;
    }

    /**
     * Create end_of_shift report
     * @param BranchShift $shift
     * @param $total_work_time
     * @param $employee_budget
     * @param $ratio
     */
    protected function createEndOfShiftReport($shift, $total_work_time = 0, $employee_budget = 0, $ratio = 0) {
        $end_of_shift_report = new EndOfShiftReport();
        $end_of_shift_report->setBranchShift($shift);
        $end_of_shift_report->setEmployeeTotalWorkTime($total_work_time);
        $end_of_shift_report->setEmployeeBudget($employee_budget);
        $end_of_shift_report->setRatio($ratio);
        $this->em->persist($end_of_shift_report);
        $this->em->flush();
    }

    /**
     * Close the shift.
     * @param BranchShift $shift
     * @param EndOfShiftReport $end_of_shift_report
     * @param $check_employee
     * @return mixed
     */
    protected function closeOneShift($shift, $end_of_shift_report, $check_employee = true) {
        $close_shift_employee = [];
        if($check_employee) {
            /** @var $history_employee Collection HistoryEmployeeRole */
            $history_employee = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')
                ->getHistoryEmployeeRoleByParam(array('shift' => $shift->getId(), 'date_start' => date('Y-m-d H:i:s'),
                    'time_open' => date('H:i:s', strtotime("-{$this->config_limit_time_open} minutes", strtotime($shift->getTimeOpen()->format('H:i:s')))), 'time_close_null' => true));
            if ($history_employee) {
                foreach ($history_employee as $employee) {
                    /** @var $employee HistoryEmployeeRole*/
                    $employee->setDateEnd(new \DateTime(date('Y-m-d H:i:s')));
                    $close_shift_employee[] = array('history_id' => $employee->getId(), 'shift_id' => $employee->getBranchShift()->getId());
                    $history_employee_role = new HistoryEmployeeRole();
                    $history_employee_role->setEmployee($employee->getEmployee());
                    $history_employee_role->setRole($employee->getRole());
                    $history_employee_role->setBranchShift($shift);
                    $history_employee_role->setDateStart(new \DateTime(date('Y-m-d H:i:s')));
                    $this->em->persist($employee);
                    $this->em->persist($history_employee_role);
                    $this->em->flush();
                }
            }
        }
        $total_work_time = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')->calculateTotalWorkTime($shift,
            array(
                'date' => date('Y-m-d H:i:s'),
                'time_open' => $shift->getTimeOpen(),
                'time_close' => $shift->getTimeClose(),
                'filter_limit_open_time' => $this->config_limit_time_open));
        $employee_budget = $this->em->getRepository('ApiBundle:HistoryEmployeeRole')
            ->calculateEmployeeBudget($shift, array(
                'date' => date('Y-m-d H:i:s'),
                'time_open' => $shift->getTimeOpen(),
                'time_close' => $shift->getTimeClose(),
                'filter_limit_open_time' => $this->config_limit_time_open));
        $ratio = $this->em->getRepository('ApiBundle:Report\CashierReport')->calculateRatio($shift->getBranch()->getId(), !empty($employee_budget[0]) ? $employee_budget[0] : 0);
        $end_of_shift_report->setWorkdayEndTime(new \DateTime(date('H:i:s')));
        $end_of_shift_report->setEmployeeTotalWorkTime(!empty($total_work_time[0]) ? $total_work_time[0] : 0);
        $end_of_shift_report->setEmployeeBudget(!empty($employee_budget[0]) ? $employee_budget[0] : 0);
        $end_of_shift_report->setRatio(!empty($ratio[0]) ? $ratio[0] : 0);
        $end_of_shift_report->setUpdated(new \DateTime(date('Y-m-d H:i:s')));
        $end_of_shift_report->setClosed(true);
        $this->em->persist($end_of_shift_report);
        $this->em->flush();

        return $close_shift_employee;
    }

}