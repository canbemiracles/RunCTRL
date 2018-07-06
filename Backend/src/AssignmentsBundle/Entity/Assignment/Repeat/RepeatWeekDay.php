<?php

namespace AssignmentsBundle\Entity\Assignment\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepeatWeekDay
 *
 * @ORM\Table(name="assignment_repeat_week_day")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Repeat\RepeatWeekDayRepository")
 */
class RepeatWeekDay
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment", inversedBy="repeat_week_days")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $assignment;

    /**
     * @ORM\Column(name="week_day", type="integer", nullable=true)
     */
    protected $week_day;


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
     * Set weekDay
     *
     * @param integer $weekDay
     *
     * @return RepeatWeekDay
     */
    public function setWeekDay($weekDay)
    {
        $this->week_day = $weekDay;

        return $this;
    }

    /**
     * Get weekDay
     *
     * @return integer
     */
    public function getWeekDay()
    {
        return $this->week_day;
    }

    /**
     * Set assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return RepeatWeekDay
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
