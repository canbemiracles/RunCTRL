<?php

namespace ApiBundle\Entity\Report;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
/**
 * CashierReport
 *
 * @ORM\Table(name="cashier_report")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Report\CashierReportRepository")
 */
class CashierReport extends AbstractReport
{
    /**
     * @ORM\Column(type="string", length=20)
     */
    protected $payment_method;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $amount;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchStation", inversedBy="cashier_reports")
     * @ORM\JoinColumn(name="branch_station_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_station;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Currency")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $currency;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\Report\CashierReportGroup", mappedBy="cashier_report")
     */
    protected $group;

    /**
     * Set branchStation
     *
     * @param \ApiBundle\Entity\BranchStation $branchStation
     *
     * @return CashierReport
     */
    public function setBranchStation(\ApiBundle\Entity\BranchStation $branchStation = null)
    {
        $this->branch_station = $branchStation;

        return $this;
    }

    /**
     * Get branchStation
     *
     * @return \ApiBundle\Entity\BranchStation
     */
    public function getBranchStation()
    {
        return $this->branch_station;
    }

    /**
     * Set paymentMethod
     *
     * @param string $paymentMethod
     *
     * @return CashierReport
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->payment_method = $paymentMethod;

        return $this;
    }

    /**
     * Get paymentMethod
     *
     * @return string
     */
    public function getPaymentMethod()
    {
        return $this->payment_method;
    }

    /**
     * Set amount
     *
     * @param float $amount
     *
     * @return CashierReport
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set currency
     *
     * @param \ApiBundle\Entity\Currency $currency
     *
     * @return CashierReport
     */
    public function setCurrency(\ApiBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \ApiBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function getBranchId()
    {
        $station = $this->getBranchStation();
        if($station != null)
        {
            $branch = $station->getBranch();
            if($branch != null) {
                return $branch->getId();
            }
        }

        return null;
    }

    public function getBranchStationId()
    {
        $station = $this->getBranchStation();
        if($station != null) {
            return $station->getId();
        }
        return null;
    }

    /**
     * Set group
     *
     * @param \ApiBundle\Entity\Report\CashierReportGroup $group
     *
     * @return CashierReport
     */
    public function setGroup(\ApiBundle\Entity\Report\CashierReportGroup $group = null)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get group
     *
     * @return \ApiBundle\Entity\Report\CashierReportGroup
     */
    public function getGroup()
    {
        return $this->group;
    }
}
