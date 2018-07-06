<?php

namespace AssignmentsBundle\Entity\Assignment\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepeatMonthDay
 *
 * @ORM\Table(name="assignment_repeat_month_day")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Repeat\RepeatMonthDayRepository")
 */
class RepeatMonthDay
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment", inversedBy="repeat_month_days")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $assignment;

    /**
     * @ORM\Column(name="month_day", type="integer", nullable=true)
     */
    protected $month_day;


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
     * Set monthDay
     *
     * @param integer $monthDay
     *
     * @return RepeatMonthDay
     */
    public function setMonthDay($monthDay)
    {
        $this->month_day = $monthDay;

        return $this;
    }

    /**
     * Get monthDay
     *
     * @return integer
     */
    public function getMonthDay()
    {
        return $this->month_day;
    }

    /**
     * Set assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return RepeatMonthDay
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
}
