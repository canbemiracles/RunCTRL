<?php

namespace AssignmentsBundle\Entity\Assignment\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepeatMonth
 *
 * @ORM\Table(name="assignment_repeat_month")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Repeat\RepeatMonthRepository")
 */
class RepeatMonth
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment", inversedBy="repeat_months")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $assignment;

    /**
     * @ORM\Column(name="month", type="integer", nullable=true)
     */
    protected $month;

    /**
     * @ORM\Column(name="month_days", type="array", nullable=true)
     */
    protected $month_days = array();


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
     * Set month
     *
     * @param integer $month
     *
     * @return RepeatMonth
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * Get month
     *
     * @return integer
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return RepeatMonth
     */
    public function setAssignment(\AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment = null)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment
     *
     * @return \AssignmentsBundle\Entity\Assignment\AbstractAssignment
     */
    public function getAssignment()
    {
        return $this->assignment;
    }

    /**
     * Set monthDays
     *
     * @param array $monthDays
     *
     * @return RepeatMonth
     */
    public function setMonthDays($monthDays)
    {
        $this->month_days = $monthDays;

        return $this;
    }

    /**
     * Get monthDays
     *
     * @return array
     */
    public function getMonthDays()
    {
        return $this->month_days;
    }

    public function addMonthDay($month_day)
    {
        $month_day = strtoupper($month_day);
        if (!in_array($month_day, $this->month_days, true)) {
            $this->month_days[] = $month_day;
        }
        return $this;
    }

    public function removeMonthDay($month_day)
    {
        if (false !== $key = array_search(strtoupper($month_day), $this->month_days, true)) {
            unset($this->month_days[$key]);
            $this->month_days = array_values($this->month_days);
        }

        return $this;
    }
}
