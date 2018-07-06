<?php

namespace ApiBundle\Service\Report;

use ApiBundle\Entity\Branch;
use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\Report\CashierReportGroup;
use ApiBundle\Repository\Report\CashierReportGroupRepository;
use ApiBundle\Repository\Report\CommodityReportRepository;
use ApiBundle\Repository\Report\ProblemReportRepository;
use ApiBundle\Repository\Report\CashierReportRepository;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use ApiBundle\Entity\Report\CashierReport as CashierReportEntity;
use ApiBundle\Service\Currency\CurrencyService as CurrencyLayer;

class CashierReport
{
    /** @var EntityManager */
    protected $em;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var CurrencyLayer */
    protected $currency_service;

    /** @var Serializer */
    protected $serializer;

    public function __construct(EntityManager $entityManager, TranslatorInterface $translator, CurrencyLayer $currency_service, Serializer $serializer)
    {
        $this->em = $entityManager;
        $this->translator = $translator;
        $this->currency_service = $currency_service;
        $this->serializer = $serializer;
    }

    public function getIncomeTodayByStation($date_start, $date_end, Company $company, BranchStation $station)
    {

        /** @var $cashier_report_repository CashierReportRepository*/
        $cashier_report_repository = $this->em->getRepository('ApiBundle:Report\CashierReport');

        /** @var $reports Collection CashierReportEntity */
        $reports = $cashier_report_repository->getStationIncomeByParam(
            date("Y-m-d H:i:s", strtotime($date_start)),
            date("Y-m-d H:i:s", strtotime($date_end)),
            $station
        );

        $total = 0;

        foreach ($reports as $report) {
            /** @var $report CashierReportEntity*/
            $total += $report->getAmount();
        }

        return array(
            'reports' => $reports,
            'total' => $total
        );
    }

    public function getIncomeTodayByGroup(Company $company, BranchStation $station, $group)
    {
        /** @var $cashier_report_group_repository CashierReportGroupRepository*/
        $cashier_report_group_repository = $this->em->getRepository('ApiBundle:Report\CashierReportGroup');

        /** @var $reports Collection CashierReportGroup*/
        $reports = $cashier_report_group_repository->getReportsByGroup($group, $station);

        $total = 0;

        foreach ($reports as $report) {
            /** @var $report CashierReportGroup*/
            $total += $report->getCashierReport()->getAmount();
        }

        return array(
            'reports' => $reports,
            'total' => $total
        );
    }

    /**
     * @param $date_start string
     * @param $date_end string
     * @param $branch Branch
     * @param $group string
     * @return mixed
    */
    public function getIncomeByBranch($date_start, $date_end, Branch $branch, $group)
    {
        /** @var $cashier_report_repository CashierReportRepository*/
        $cashier_report_repository = $this->em->getRepository('ApiBundle:Report\CashierReport');

        $response = [];

        if($group === "payment_method") {
            $cashier = $cashier_report_repository->getCashierByBranch($branch, $date_start, $date_end);
            $response = array('cashier' => null, 'total' => 0, 'branch_manager' => array(
                'name' =>  !empty($branch->getBranchManager()) ? $branch->getBranchManager()->getFullName() : null,
                'avatar' => !empty($branch->getBranchManager()) ? $branch->getBranchManager()->getAvatar() : null,
            ), 'branch_address' => $branch->getGeographicalArea());

            foreach ($cashier as $item) {
                /** @var $item CashierReportEntity*/
                if($branch->getCompany()->getCurrency() !== $item->getCurrency()) {
                    $result = $this->currency_service->convert($item->getCurrency()->getCurrency(), $branch->getCompany()->getCurrency()->getCurrency(), $item->getAmount());
                    if(!empty($result['result'])) {
                        $response['total'] += $result['result'];
                        if(!empty($response['cashier'][$item->getPaymentMethod()])) {
                            $response['cashier'][$item->getPaymentMethod()]['amount'] += $result['result'];
                        } else {
                            $response['cashier'][$item->getPaymentMethod()] = array('payment_method' => $item->getPaymentMethod(), 'amount' => $result['result']);
                        }
                    }
                } else {
                    $response['total'] += $item->getAmount();
                    if(!empty($response['cashier'][$item->getPaymentMethod()])) {
                        $response['cashier'][$item->getPaymentMethod()]['amount'] += $item->getAmount();
                    } else {
                        $response['cashier'][$item->getPaymentMethod()] = array('payment_method' => $item->getPaymentMethod(), 'amount' => $item->getAmount());
                    }
                }
            }
        }

        return $response;
    }

    /**
     * @param $date_start \DateTime
     * @param $date_end \DateTime
     * @param $branch Branch
     * @param $group int : 1 - group by hour, 2 - group by day, 3- group by month
     * @return mixed
     */
    public function getIncomeStatisticByBranch(\DateTime $date_start, \DateTime $date_end, Branch $branch, $group = 2)
    {
        /** @var $cashier_report_repository CashierReportRepository*/
        $cashier_report_repository = $this->em->getRepository('ApiBundle:Report\CashierReport');

        $groups = [];

        $avg = 0;
        $counter = 0;

        switch ($group)
        {
            case 1:
                $diff = $date_start->diff($date_end);
                if(!$diff) {
                    break;
                }
                $hours = $diff->h + 24 * $diff->days;
                for($hour = 0; $hour <= $hours; $hour++) {
                    $cashiers = $cashier_report_repository->getCashierByBranch($branch,
                        (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$hour} hour")->format("Y-m-d H:i:s"),
                        (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$hour} hour")->format("Y-m-d H:59:59"));
                    $counter += count($cashiers);
                    $groups[$hour] = array('amount' => 0, 'dateTime' => (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$hour} hour")->format("Y-m-d H:00:00"));
                    foreach ($cashiers as $item) {
                        /** @var $item CashierReportEntity*/
                        if($branch->getCompany()->getCurrency() !== $item->getCurrency()) {
                            $result = $this->currency_service->convert($item->getCurrency()->getCurrency(), $branch->getCompany()->getCurrency()->getCurrency(), $item->getAmount());
                            if(!empty($result['result'])) {
                                $groups[$hour]['amount'] += $result['result'];
                                $avg += $result['result'];
                            }
                        } else {
                            $groups[$hour]['amount'] += $item->getAmount();
                            $avg += $item->getAmount();
                        }
                    }
                }
                $avg = $counter ? $avg / $counter : 0;
                break;
            case 2:
                $diff = $date_start->diff($date_end);
                if(!$diff) {
                    break;
                }
                $days = $diff->days;
                for($day = 0; $day <= $days; $day++) {
                    $cashiers = $cashier_report_repository->getCashierByBranch($branch,
                        (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$day} day")->format("Y-m-d H:i:s"),
                        (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$day} day")->format("Y-m-d 23:59:59"));
                    $counter += count($cashiers);
                    $groups[$day] = array('amount' => 0, 'dateTime' => (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$day} day")->format("Y-m-d 00:00:00"));
                    foreach ($cashiers as $item) {
                        /** @var $item CashierReportEntity*/
                        if($branch->getCompany()->getCurrency() !== $item->getCurrency()) {
                            $result = $this->currency_service->convert($item->getCurrency()->getCurrency(), $branch->getCompany()->getCurrency()->getCurrency(), $item->getAmount());
                            if(!empty($result['result'])) {
                                $groups[$day]['amount'] += $result['result'];
                                $avg += $result['result'];
                            }
                        } else {
                            $groups[$day]['amount'] += $item->getAmount();
                            $avg += $item->getAmount();
                        }
                    }
                }
                $avg = $counter ? $avg / $counter : 0;
                break;
            case 3:
                $diff = $date_start->diff($date_end);
                if(!$diff) {
                    break;
                }
                $months = $diff->m;
                for($month = 0; $month <= $months; $month++) {
                    $current_month_days = (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$month} month")->format("t");
                    $cashiers = $cashier_report_repository->getCashierByBranch($branch,
                        (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$month} month")->format("Y-m-d H:i:s"),
                        (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$month} month")->format("Y-m-{$current_month_days} 23:59:59"));
                    $counter += count($cashiers);
                    $groups[$month] = array('amount' => 0, 'dateTime' => (new \DateTime($date_start->format("Y-m-d H:i:s")))->modify("+{$month} month")->format("Y-m-01 00:00:00"));
                    foreach ($cashiers as $item) {
                        /** @var $item CashierReportEntity*/
                        if($branch->getCompany()->getCurrency() !== $item->getCurrency()) {
                            $result = $this->currency_service->convert($item->getCurrency()->getCurrency(), $branch->getCompany()->getCurrency()->getCurrency(), $item->getAmount());
                            if(!empty($result['result'])) {
                                $groups[$month]['amount'] += $result['result'];
                                $avg += $result['result'];
                            }
                        } else {
                            $groups[$month]['amount'] += $item->getAmount();
                            $avg += $item->getAmount();
                        }
                    }
                }
                $avg = $counter ? $avg / $counter : 0;
                break;
            default:
                break;
        }

        return array(
            'groups' => $groups,
            'avg' => $avg
        );
    }

}