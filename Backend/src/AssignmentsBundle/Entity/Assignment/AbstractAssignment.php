<?php

namespace AssignmentsBundle\Entity\Assignment;

use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractAssignment
 * @ORM\Table(name="`assignment")
 * @ORM\EntityListeners({"AssignmentsBundle\EntityListener\AbstractAssignmentListener"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *      "standard" = "StandardTask",
 *      "question_answer_list" = "AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList",
 *      "question_numeric" = "AssignmentsBundle\Entity\Assignment\Question\QuestionNumeric",
 *      "question_range" = "AssignmentsBundle\Entity\Assignment\Question\QuestionRange",
 *      "question_text" = "AssignmentsBundle\Entity\Assignment\Question\QuestionText",
 *      "question_yes_no" = "AssignmentsBundle\Entity\Assignment\Question\QuestionYesNo",
 *      "checklist" = "AssignmentsBundle\Entity\Assignment\Checklist\Checklist"
 *     }
 * )
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository")
 */
abstract class AbstractAssignment
{
    const PRIORITY_NOTIFY_MANAGER = 1;
    const PRIORITY_NOTIFY_SUPERVISOR = 2;
    const PRIORITY_NOTIFY_COMPANY_OWNER = 3;
    const IMPORTANCE_NOTIFY_MANAGER = 1;
    const IMPORTANCE_NOTIFY_SUPERVISOR = 2;
    const IMPORTANCE_NOTIFY_COMPANY_OWNER = 3;

    const REPEAT_DAILY = 1;
    const REPEAT_WEEKLY = 2;
    const REPEAT_MONTHLY = 3;
    const REPEAT_YEARLY = 4;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Role\AbstractBranchStationRole", inversedBy="assignments")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $role;

    /**
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    protected $priority = AbstractAssignment::PRIORITY_NOTIFY_MANAGER;

    /**
     * @ORM\Column(name="importance", type="integer", nullable=true)
     */
    protected $importance = AbstractAssignment::IMPORTANCE_NOTIFY_MANAGER;

    /**
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    protected $start_time;

    /**
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    protected $end_time;

    /**
     * null = no repeat
     * 1 = daily, 2 = weekly, 3 = monthly, 4 = every year
     * @ORM\Column(name="repeat_unit", type="integer", nullable=true)
     */
    protected $repeat_unit;

    /**
     * @ORM\Column(name="repeat_subunit", type="integer", nullable=true)
     */
    protected $repeat_subunit;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Assignment\Repeat\RepeatWeekDay", mappedBy="assignment", cascade={"persist"})
     */
    protected $repeat_week_days;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonthDay", mappedBy="assignment", cascade={"persist"})
     */
    protected $repeat_month_days;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonth", mappedBy="assignment", cascade={"persist"})
     */
    protected $repeat_months;

    /**
     * @ORM\Column(name="repeat_week", type="integer", nullable=true)
     */
    protected $repeat_week;

    /**
     * @ORM\Column(name="snooze_count", type="integer", nullable=true)
     */
    protected $snooze_count = 0;

    /**
     * @ORM\Column(name="snooze_time", type="integer", nullable=true)
     */
    protected $snooze_time = 300;
    
    /**
     * @ORM\Column(name="snooze_until", type="datetime", nullable=true)
     */
    protected $snooze_until;

    /**
     * @ORM\Column(name="snooze_max", type="integer", nullable=true)
     */
    protected $snooze_max;

    /**
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @ORM\Column(name="view_time", type="integer", nullable=true)
     */
    protected $view_time;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Answer\AbstractAnswer", mappedBy="assignment")
     */
    protected $answers;

    /**
     * @ORM\Column(name="last_sent_date", type="date", nullable=true)
     */
    protected $lastSentDate = null;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchStation", inversedBy="assignments")
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $station;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Assignment\Repeat\AssignmentRepeatHistory", mappedBy="assignment", cascade={"persist"})
     */
    protected $repeat_histories;

    /**
     * @ORM\Column(name="depth", type="integer")
     */
    protected $depth = 3;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = false;


    public function __construct()
    {
        $this->snooze_until = null;
        $current_time = date('Y-m-d H:i:s');
        $this->start_time = new \DateTime($current_time);
        //TODO: move constant to config
        //FIXME: is this hardcoded and should be refactored?
        $this->end_time = (new \DateTime($current_time))->modify("+15 minutes");
        $this->answers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->repeat_week_days = new \Doctrine\Common\Collections\ArrayCollection();
        $this->repeat_month_days = new \Doctrine\Common\Collections\ArrayCollection();
        $this->repeat_months = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set snoozeCount
     *
     * @param integer $snoozeCount
     *
     * @return AbstractAssignment
     */
    public function setSnoozeCount($snoozeCount)
    {
        $this->snooze_count = $snoozeCount;

        return $this;
    }

    /**
     * Get snoozeCount
     *
     * @return integer
     */
    public function getSnoozeCount()
    {
        return $this->snooze_count;
    }

    /**
     * Set snoozeUntil
     *
     * @param \DateTime $snoozeUntil
     *
     * @return AbstractAssignment
     */
    public function setSnoozeUntil($snoozeUntil)
    {
        $this->snooze_until = $snoozeUntil;

        return $this;
    }

    /**
     * Get snoozeUntil
     *
     * @return \DateTime
     */
    public function getSnoozeUntil()
    {
        return $this->snooze_until;
    }

    /**
     * Set snoozeMax
     *
     * @param integer $snoozeMax
     *
     * @return AbstractAssignment
     */
    public function setSnoozeMax($snoozeMax)
    {
        $this->snooze_max = $snoozeMax;

        return $this;
    }

    /**
     * Get snoozeMax
     *
     * @return integer
     */
    public function getSnoozeMax()
    {
        return $this->snooze_max;
    }

    /**
     * Set employee
     *
     * @param \ApiBundle\Entity\Role\AbstractBranchStationRole $role
     *
     * @return AbstractAssignment
     */
    public function setRole(\ApiBundle\Entity\Role\AbstractBranchStationRole $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \ApiBundle\Entity\Role\AbstractBranchStationRole
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return AbstractAssignment
     */
    public function setStartTime($startTime)
    {
        if($startTime == null) {
            $this->start_time = null;
        } else {
            $this->start_time = clone $startTime;
        }

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        if($this->start_time == null) {
            return null;
        }

        return clone $this->start_time;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return AbstractAssignment
     */
    public function setEndTime($endTime)
    {
        if($endTime == null) {
            $this->end_time = null;
        } else {
            $this->end_time = clone $endTime;
        }

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        if($this->end_time == null) {
            return null;
        }

        return clone $this->end_time;
    }

    /**
     * Set repeatUnit
     *
     * @param integer $repeatUnit
     *
     * @return AbstractAssignment
     */
    public function setRepeatUnit($repeatUnit)
    {
        $this->repeat_unit = $repeatUnit;

        return $this;
    }

    /**
     * Get repeatUnit
     *
     * @return integer
     */
    public function getRepeatUnit()
    {
        return $this->repeat_unit;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return AbstractAssignment
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set viewTime
     *
     * @param integer $viewTime
     *
     * @return AbstractAssignment
     */
    public function setViewTime($viewTime)
    {
        $this->view_time = $viewTime;

        return $this;
    }

    /**
     * Get viewTime
     *
     * @return integer
     */
    public function getViewTime()
    {
        return $this->view_time;
    }

    /**
     * Add answer
     *
     * @param \AssignmentsBundle\Entity\Answer\AbstractAnswer $answer
     *
     * @return AbstractAssignment
     */
    public function addAnswer(\AssignmentsBundle\Entity\Answer\AbstractAnswer $answer)
    {
        $this->answers[] = $answer;

        return $this;
    }

    /**
     * Remove answer
     *
     * @param \AssignmentsBundle\Entity\Answer\AbstractAnswer $answer
     */
    public function removeAnswer(\AssignmentsBundle\Entity\Answer\AbstractAnswer $answer)
    {
        $this->answers->removeElement($answer);
    }

    /**
     * Get answers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnswers()
    {
        return $this->answers;
    }

    /**
     * Set snoozeTime
     *
     * @param integer $snoozeTime
     *
     * @return AbstractAssignment
     */
    public function setSnoozeTime($snoozeTime)
    {
        $this->snooze_time = $snoozeTime;

        return $this;
    }

    /**
     * Get snoozeTime
     *
     * @return integer
     */
    public function getSnoozeTime()
    {
        return $this->snooze_time;
    }

    /**
     * Set lastSentDate
     *
     * @param \DateTime $lastSentDate
     *
     * @return AbstractAssignment
     */
    public function setLastSentDate($lastSentDate)
    {
        $this->lastSentDate = $lastSentDate;

        return $this;
    }

    /**
     * Get lastSentDate
     *
     * @return \DateTime
     */
    public function getLastSentDate()
    {
        return $this->lastSentDate;
    }


    /**
     * Set priority
     *
     * @param integer $priority
     *
     * @return AbstractAssignment
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return integer
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set importance
     *
     * @param integer $importance
     *
     * @return AbstractAssignment
     */
    public function setImportance($importance)
    {
        $this->importance = $importance;

        return $this;
    }

    /**
     * Get importance
     *
     * @return integer
     */
    public function getImportance()
    {
        return $this->importance;
    }

    /**
     * Set station
     *
     * @param \ApiBundle\Entity\BranchStation
     *
     * @return AbstractAssignment
     */
    public function setStation(\ApiBundle\Entity\BranchStation $station = null)
    {
        $this->station = $station;

        return $this;
    }

    /**
     * Get station
     *
     * @return \ApiBundle\Entity\BranchStation
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * Set repeatSubunit
     *
     * @param integer $repeatSubunit
     *
     * @return AbstractAssignment
     */
    public function setRepeatSubunit($repeatSubunit)
    {
        $this->repeat_subunit = $repeatSubunit;

        return $this;
    }

    /**
     * Get repeatSubunit
     *
     * @return integer
     */
    public function getRepeatSubunit()
    {
        return $this->repeat_subunit;
    }

    /**
     * Set repeatWeek
     *
     * @param integer $repeatWeek
     *
     * @return AbstractAssignment
     */
    public function setRepeatWeek($repeatWeek)
    {
        $this->repeat_week = $repeatWeek;

        return $this;
    }

    /**
     * Get repeatWeek
     *
     * @return integer
     */
    public function getRepeatWeek()
    {
        return $this->repeat_week;
    }

    public function getStartTimeTimeZone()
    {
        if(!empty($this->getRole()) && !empty($this->getStartTime())) {
            $offset = intval($this->getRole()->getBranchStation()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $time = (new \DateTime())->setTimestamp($this->getStartTime()->getTimestamp());
            return !empty($time) ? $time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }

    public function getEndTimeTimeZone()
    {
        if(!empty($this->getRole()) && !empty($this->getEndTime())) {
            $offset = intval($this->getRole()->getBranchStation()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $time = (new \DateTime())->setTimestamp($this->getEndTime()->getTimestamp());
            return !empty($time) ? $time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }

    public function getSnoozeUntilTimeZone()
    {
        if(!empty($this->getRole()) && !empty($this->getSnoozeUntil())) {
            $offset = intval($this->getRole()->getBranchStation()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $snooze = (new \DateTime())->setTimestamp($this->getSnoozeUntil()->getTimestamp());
            return !empty($snooze) ? $snooze->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }

    /**
     * Add repeatWeekDay
     *
     * @param \AssignmentsBundle\Entity\Assignment\Repeat\RepeatWeekDay $repeatWeekDay
     *
     * @return AbstractAssignment
     */
    public function addRepeatWeekDay(\AssignmentsBundle\Entity\Assignment\Repeat\RepeatWeekDay $repeatWeekDay)
    {
        $repeatWeekDay->setAssignment($this);
        $this->repeat_week_days->add($repeatWeekDay);

        return $this;
    }

    /**
     * Remove repeatWeekDay
     *
     * @param \AssignmentsBundle\Entity\Assignment\Repeat\RepeatWeekDay $repeatWeekDay
     */
    public function removeRepeatWeekDay(\AssignmentsBundle\Entity\Assignment\Repeat\RepeatWeekDay $repeatWeekDay)
    {
        $this->repeat_week_days->removeElement($repeatWeekDay);
    }

    /**
     * Get repeatWeekDays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepeatWeekDays()
    {
        return $this->repeat_week_days;
    }

    /**
     * Add repeatMonthDay
     *
     * @param \AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonthDay $repeatMonthDay
     *
     * @return AbstractAssignment
     */
    public function addRepeatMonthDay(\AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonthDay $repeatMonthDay)
    {
        $repeatMonthDay->setAssignment($this);
        $this->repeat_month_days->add($repeatMonthDay);

        return $this;
    }

    /**
     * Remove repeatMonthDay
     *
     * @param \AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonthDay $repeatMonthDay
     */
    public function removeRepeatMonthDay(\AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonthDay $repeatMonthDay)
    {
        $this->repeat_month_days->removeElement($repeatMonthDay);
    }

    /**
     * Get repeatMonthDays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepeatMonthDays()
    {
        return $this->repeat_month_days;
    }

    /**
     * Add repeatMonth
     *
     * @param \AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonth $repeatMonth
     *
     * @return AbstractAssignment
     */
    public function addRepeatMonth(\AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonth $repeatMonth)
    {
        $repeatMonth->setAssignment($this);
        $this->repeat_months->add($repeatMonth);

        return $this;
    }

    /**
     * Remove repeatMonth
     *
     * @param \AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonth $repeatMonth
     */
    public function removeRepeatMonth(\AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonth $repeatMonth)
    {
        $this->repeat_months->removeElement($repeatMonth);
    }

    /**
     * Get repeatMonths
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepeatMonths()
    {
        return $this->repeat_months;
    }

    /**
     * Get depth
     *
     * @return integer
     */
    public function getDepth()
    {
        return $this->depth;
    }

    /**
     * Set depth
     *
     * @param integer $depth
     *
     * @return AbstractAssignment
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return AbstractAssignment
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

}
