<?php

namespace ApiBundle\Service\Branch;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\ShiftDay;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\BranchManager;
use ApiBundle\Entity\User\Supervisor;
use ApiBundle\Repository\BranchShiftRepository;
use ApiBundle\Repository\EmployeeRepository;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use ApiBundle\Repository\Report\CashierReportRepository;
use ApiBundle\Repository\Role\AbstractBranchStationRoleRepository;
use ApiBundle\Service\BranchStationRole\BranchStationRoleManagement;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use ApiBundle\Entity\Report\CashierReport as CashierReportEntity;
use ApiBundle\Service\Currency\CurrencyService as CurrencyLayer;

/**
 * Created by PhpStorm.
 * User: mail
 * Date: 27.11.2017
 * Time: 13:22
 */

class BranchService
{

    /** @var  EntityManager */
    protected $entityManager;

    /** @var  BranchStationRoleManagement */
    protected $branchStationRoleManagement;

    /** @var TranslatorInterface */
    protected $translator;

    protected $config_limit_time_open;

    /** @var CurrencyLayer */
    protected $currency_service;

    public function __construct(EntityManager $entityManager, BranchStationRoleManagement $branchStationRoleManagement,
                                TranslatorInterface $translator, $config_limit_time_open, CurrencyLayer $currency_service)
    {
        $this->entityManager = $entityManager;
        $this->branchStationRoleManagement = $branchStationRoleManagement;
        $this->translator = $translator;
        $this->config_limit_time_open = $config_limit_time_open;
        $this->currency_service = $currency_service;
    }

    public function attachEmployeeToBranch(Branch $branch, int $employee_id)
    {
        $em = $this->entityManager;

        /** @var EmployeeRepository $employee_repository */
        $employee_repository = $em->getRepository('ApiBundle\Entity\Employee');

        /** @var Employee $employee */
        $employee = $employee_repository->find($employee_id);

        if(!$employee || !($employee instanceof Employee))
        {
            throw new BadRequestHttpException("Employee not found");
        }

        if($employee->getBranches()->contains($branch))
        {
            throw new BadRequestHttpException('Employee already attached to this branch');
        }

        $employee->addBranch($branch);

        $em->persist($employee);
        $em->flush();

        return new JsonResponse(['message' => 'Employee successfully attached to branch']);
    }

    public function detachEmployeeToBranch(Branch $branch, int $employee_id)
    {
        $em = $this->entityManager;

        /** @var EmployeeRepository $employee_repository */
        $employee_repository = $em->getRepository('ApiBundle\Entity\Employee');

        /** @var Employee $employee */
        $employee = $employee_repository->find($employee_id);

        if(!$employee || !($employee instanceof Employee))
        {
            throw new BadRequestHttpException("Employee not found");
        }

        if(!$employee->getBranches()->contains($branch))
        {
            throw new BadRequestHttpException('Employee already not attached to this branch');
        }

        $employee->removeBranch($branch);

        $em->persist($employee);
        $em->flush();

        return new JsonResponse(['message' => 'Employee successfully detached to branch']);
    }

    public function attachBranchManagerToBranch(Branch $branch, BranchManager $branchManager)
    {
        $em = $this->entityManager;

        /** @var BranchManager $previousBranchManager */
        $previousBranchManager = $branch->getBranchManager();

        //removing existing Branch Manager from Branch
        if($previousBranchManager != null)
        {
            $previousBranchManager->setBranch(null);
            $em->persist($previousBranchManager);
            $this->entityManager->flush($previousBranchManager);
        }
        
        //Attaching branch manager
        $branch->setBranchManager($branchManager);
        $branchManager->setBranch($branch);

        $this->entityManager->persist($branchManager);
        $this->entityManager->persist($branch);
        $this->entityManager->flush();
    }

    /**
     * @param $date_start
     * @param $date_end
     * @param Company $company
     * @return mixed
     */
    public function getStatisticIncome($date_start, $date_end, $company)
    {
        /** @var $cashier_report_repository CashierReportRepository*/
        $cashier_report_repository = $this->entityManager->getRepository('ApiBundle:Report\CashierReport');
        $branches = [];
        foreach ($company->getBranches() as $branch)
        {
            /** @var $reports Collection CashierReport*/
            $reports = $cashier_report_repository->getCashierByBranch($branch, $date_start, $date_end);
            if(count($reports) == 0) {
                continue;
            }
            $income = 0;
            foreach ($reports as $report) {
                /** @var $report CashierReportEntity*/
                if($branch->getCompany()->getCurrency() !== $report->getCurrency()) {
                    $result = $this->currency_service->convert($report->getCurrency()->getCurrency(), $branch->getCompany()->getCurrency()->getCurrency(), $report->getAmount());
                    if(!empty($result['result'])) {
                        $income += $result['result'];
                    }
                } else {
                    $income += $report->getAmount();
                }
            }
            /** @var $branch Branch*/
            $branches[] = array(
                'branch_id' => $branch->getId(),
                'address' => !empty($branch->getGeographicalArea()) ? $branch->getGeographicalArea()->getStreetAddress() : null,
                'income' => $income
            );
        }

        $avg = 0;

        foreach ($branches as $branch) {
            $avg += $branch['income'];
        }

        return array(
            'branches' => $branches,
            'avg' => count($branches) > 0 ? floatval($avg) / count($branches) : 0
        );
    }

    public function getRolesStatistic(Branch $branch)
    {
        $results = [];
        foreach($branch->getStations() as $station)
        {
            $results = array_merge($results,$this->branchStationRoleManagement->getRolesWithShifts($station));
        }
        return $results;
    }

    public function getDataLiveEmployeesShifts(Branch $branch)
    {
        $response = [];

        // Get the current day of the week number
        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var ShiftDay $shift_day*/
        $shift_day = $this->entityManager->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return $this->translator->trans("shift_management.shift_day_not_found");
        }

        /** @var $shift_repository BranchShiftRepository */
        $shift_repository = $this->entityManager->getRepository('ApiBundle:BranchShift');

        /** @var $current_shift BranchShift */
        $current_shift = $shift_repository->getCurrentShift([
            'branch' => $branch->getId(),
            'filter_shift_day' => $shift_day->getId(),
            'filter_time_open' => date('H:i:s', strtotime("+{$this->config_limit_time_open} minutes", strtotime(date('Y-m-d H:i:s')))),
            'filter_time_close' => date('H:i:s')]);

        /** @var $employees array Employee */
        $employees = $branch->getEmployees();

        /** @var $history_role_repository HistoryEmployeeRoleRepository*/
        $history_role_repository = $this->entityManager->getRepository('ApiBundle:HistoryEmployeeRole');

        $days = array("monday","tuesday","wednesday","thursday","friday", "saturday", "sunday");

        if(empty($current_shift)) {
            /** @var $prev_shift BranchShift */
            $prev_shift = $shift_repository->prevShift($branch, $day_of_week, date('H:i:s'));
            if(!empty($prev_shift)) {
                foreach($employees as $employee) {
                    /** @var $employee Employee */
                    /** @var $history HistoryEmployeeRole */
                    $history = $history_role_repository->getOneRecord(array(
                        'employee' => $employee,
                        'shift' => $prev_shift,
                        'date_end' => date('Y-m-d', strtotime("previous {$days[$prev_shift->getShiftDay()->getDay()-1]}")),
                        'sort_date_start_asc' => true,
                        'branch' => $branch));
                    $response[] = !empty($history) ? array(
                        'history' => $history,
                        'done_tasks' => $this->branchStationRoleManagement->getDoneTasksByEmployee($employee, $prev_shift),
                        'pending_tasks' => $this->branchStationRoleManagement->getPendingTasksByEmployee($employee, $prev_shift),
                        'problem_tasks' => count($this->branchStationRoleManagement->getProblemTasksByEmployee($employee, $prev_shift)),
                    ) : array('id_employee' => $employee->getId());
                }
            }
        } else {
            foreach($employees as $employee) {
                /** @var $employee Employee */
                /** @var $history HistoryEmployeeRole */
                $history = $history_role_repository->getOneRecord(array(
                    'employee' => $employee,
                    'shift' => $current_shift,
                    'date_end' => date('Y-m-d'),
                    'sort_date_start_asc' => true,
                    'branch' => $branch));
                $response[] = !empty($history) ? array(
                    'history' => $history,
                    'done_tasks' => $this->branchStationRoleManagement->getDoneTasksByEmployee($employee, $current_shift),
                    'pending_tasks' => $this->branchStationRoleManagement->getPendingTasksByEmployee($employee, $current_shift),
                    'problem_tasks' => count($this->branchStationRoleManagement->getProblemTasksByEmployee($employee, $current_shift)),
                ) : array('id_employee' => $employee->getId());
            }
        }

        return $response;
    }

    public function simpleData(Company $company, AbstractUser $user = null)
    {
        $branches = [];
        if(is_null($user)) {
            $branches = $company->getBranches();
        } else {
            if($user instanceof Supervisor) {
                $branches = $user->getBranches();
            } elseif($user instanceof BranchManager) {
                $branches = $user->getBranch();
            }
        }
        $response = [];
        if(!empty($branches)) {
            foreach ($branches as $branch) {
                /** @var $branch Branch */
                $response[] = [
                    'id' => $branch->getId(),
                    'geographical_area' => $branch->getGeographicalArea()
                ];
            }
        }

        return $response;
    }

    /**
     * @param Company $company
     * @return mixed
    */
    public function getRegionsBranchesByCompany(Company $company)
    {
        /** @var $branches Collection Branch*/
        $branches = $company->getBranches();
        $regions = [];
        foreach($branches as $branch) {
            /** @var $branch Branch*/
            if(!empty($branch->getGeographicalArea())) {
                $regions[] = $branch->getGeographicalArea()->getRegion();
            }
        }
        return $regions;
    }


    /**
     * @param Branch $branch
     * @param BranchStation $station
     * @return mixed
    */
    public function getEmployeesGroupByShift($branch, $station = null)
    {
        $response = [];
        /** @var $repository AbstractBranchStationRoleRepository*/
        $repository = $this->entityManager->getRepository(AbstractBranchStationRole::class);
        foreach ($branch->getShifts() as $shift) {
            $employees = array();
            if(!empty($shift->getEmployees())) {
                foreach ($shift->getEmployees() as $employee) {
                    /** @var $employee Employee*/
                    $data = array(
                        'id' => $employee->getId(),
                        'first_name' => $employee->getFirstName(),
                        'last_name' => $employee->getLastName(),
                        'avatar' => $employee->getAvatar(),
                        'roles' => $employee->getRolesInfo()
                    );
                    if(!empty($station)) {
                        $station_roles = array(
                            'station_id' => $station->getId(),
                            'roles' => $repository->getRolesByStation($station, $employee)
                        );
                        $data = array_merge($data, array('station_roles' => $station_roles));
                    }
                    $employees[] = $data;
                }
            }
            /** @var $shift BranchShift*/
            $response[] = array(
                'branch_shift' => array(
                    'id' => $shift->getId(),
                    'name' => $shift->getName(),
                    'time_open' => $shift->getTimeOpen(),
                    'time_open_custom' => $shift->getCustomTimeOpen(),
                    'time_close' => $shift->getCustomTimeOpen(),
                    'time_close_custom' => $shift->getCustomTimeClose(),
                    'shift_day' => $shift->getShiftDay()
                ),
                'employees' => $employees
            );

        }

        return $response;
    }

}