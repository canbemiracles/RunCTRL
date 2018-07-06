<?php

namespace ApiBundle\Entity\Report;

use Doctrine\ORM\Mapping as ORM;

/**
 * CashierReportGroup
 *
 * @ORM\Table(name="cashier_report_group")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Report\CashierReportGroupRepository")
 */
class CashierReportGroup
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer")
     */
    protected $group_report;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\Report\CashierReport", inversedBy="group")
     * @ORM\JoinColumn(name="cashier_report_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $cashier_report;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set group
     *
     * @param integer $group_report
     *
     * @return CashierReportGroup
     */
    public function setGroupReport($group_report)
    {
        $this->group_report = $group_report;

        return $this;
    }

    /**
     * Get group
     *
     * @return integer
     */
    public function getGroupReport()
    {
        return $this->group_report;
    }

    /**
     * Set cashierReport
     *
     * @param \ApiBundle\Entity\Report\CashierReport $cashierReport
     *
     * @return CashierReportGroup
     */
    public function setCashierReport(\ApiBundle\Entity\Report\CashierReport $cashierReport = null)
    {
        $this->cashier_report = $cashierReport;

        return $this;
    }

    /**
     * Get cashierReport
     *
     * @return \ApiBundle\Entity\Report\CashierReport
     */
    public function getCashierReport()
    {
        return $this->cashier_report;
    }
}
