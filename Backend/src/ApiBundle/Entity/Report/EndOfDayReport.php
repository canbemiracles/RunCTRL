<?php

namespace ApiBundle\Entity\Report;

use Doctrine\ORM\Mapping as ORM;

/**
 * EndOfDayReport
 *
 * @ORM\Table(name="end_of_day_report")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Report\EndOfDayReportRepository")
 */
class EndOfDayReport extends AbstractReport
{
    /**
     * @ORM\Column(type="time")
     */
    protected $workday_start_time;

    /**
     * @ORM\Column(type="time", nullable=true)
     */
    protected $workday_end_time;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Company")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $company;

    /**
     * @ORM\Column(type="integer")
     */
    protected $employee_total_work_time;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $employee_budget ;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $ratio;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->workday_start_time = new \DateTime(date('H:i:s'));
    }

    /**
     * Set workdayStartTime
     *
     * @param \DateTime $workdayStartTime
     *
     * @return EndOfDayReport
     */
    public function setWorkdayStartTime($workdayStartTime)
    {
        $this->workday_start_time = $workdayStartTime;

        return $this;
    }

    /**
     * Get workdayStartTime
     *
     * @return \DateTime
     */
    public function getWorkdayStartTime()
    {
        return $this->workday_start_time;
    }

    /**
     * Set workdayEndTime
     *
     * @param \DateTime $workdayEndTime
     *
     * @return EndOfDayReport
     */
    public function setWorkdayEndTime($workdayEndTime)
    {
        $this->workday_end_time = $workdayEndTime;

        return $this;
    }

    /**
     * Get workdayEndTime
     *
     * @return \DateTime
     */
    public function getWorkdayEndTime()
    {
        return $this->workday_end_time;
    }

    /**
     * Set employeeTotalWorkTime
     *
     * @param integer $employeeTotalWorkTime
     *
     * @return EndOfDayReport
     */
    public function setEmployeeTotalWorkTime($employeeTotalWorkTime)
    {
        $this->employee_total_work_time = $employeeTotalWorkTime;

        return $this;
    }

    /**
     * Get employeeTotalWorkTime
     *
     * @return integer
     */
    public function getEmployeeTotalWorkTime()
    {
        return $this->employee_total_work_time;
    }

    /**
     * Set employeeBudget
     *
     * @param float $employeeBudget
     *
     * @return EndOfDayReport
     */
    public function setEmployeeBudget($employeeBudget)
    {
        $this->employee_budget = $employeeBudget;

        return $this;
    }

    /**
     * Get employeeBudget
     *
     * @return float
     */
    public function getEmployeeBudget()
    {
        return $this->employee_budget;
    }

    /**
     * Set ratio
     *
     * @param float $ratio
     *
     * @return EndOfDayReport
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;

        return $this;
    }

    /**
     * Get ratio
     *
     * @return float
     */
    public function getRatio()
    {
        return $this->ratio;
    }

    /**
     * Set company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return EndOfDayReport
     */
    public function setCompany(\ApiBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \ApiBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }
}
