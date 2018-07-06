<?php

namespace ApiBundle\Entity\Report;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * EndOfShiftReport
 *
 * @ORM\Table(name="end_of_shift_report")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Report\EndOfShiftReportRepository")
 */
class EndOfShiftReport extends AbstractReport
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
     * @ORM\Column(type="integer")
     */
    protected $employee_total_work_time;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $employee_budget;

    /**
     * @ORM\Column(type="float", precision=2)
     */
    protected $ratio;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $closed;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->workday_start_time = new \DateTime(date('H:i:s'));
        $this->closed = 0;
    }

    /**
     * Set workdayStartTime
     *
     * @param \DateTime $workdayStartTime
     *
     * @return EndOfShiftReport
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
     * @return EndOfShiftReport
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
     * @return EndOfShiftReport
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
     * @return EndOfShiftReport
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
     * @return EndOfShiftReport
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
     * Set closed
     *
     * @param boolean $closed
     *
     * @return EndOfShiftReport
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean
     */
    public function getClosed()
    {
        return $this->closed;
    }
}
