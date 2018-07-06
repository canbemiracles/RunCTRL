<?php

namespace AssignmentsBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceNotification
 *
 * @ORM\Table(name="device_notification")
 * @ORM\EntityListeners({"AssignmentsBundle\EntityListener\DeviceNotificationListener"})
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *          "message" = "DeviceNotificationMessage",
 *          "notification_role" = "DeviceNotificationRole",
 *          "notification_station" = "DeviceNotificationStation",
 *          "notification_branch" = "DeviceNotificationBranch"
 *     }
 * )
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\DeviceNotificationRepository")
 */
abstract class DeviceNotification
{
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
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="view_time", type="integer", nullable=true)
     */
    protected $view_time;

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
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\Repeat\RepeatWeekDay", mappedBy="notification", cascade={"persist"})
     */
    protected $repeat_week_days;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\Repeat\RepeatMonthDay", mappedBy="notification", cascade={"persist"})
     */
    protected $repeat_month_days;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\Repeat\RepeatMonth", mappedBy="notification", cascade={"persist"})
     */
    protected $repeat_months;

    /**
     * @ORM\Column(name="repeat_week", type="integer", nullable=true)
     */
    protected $repeat_week;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\Repeat\NotificationRepeatHistory", mappedBy="notification", cascade={"persist"})
     */
    protected $repeat_histories;
    
    /**
     * @ORM\Column(name="last_sent_date", type="date", nullable=true)
     */
    protected $lastSentDate = null;

    /**
     * @ORM\Column(name="depth", type="integer")
     */
    protected $depth = 3;

    /**
     * @ORM\Column(name="enabled", type="boolean")
     */
    protected $enabled = false;

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
     * Set title
     *
     * @param string $title
     *
     * @return DeviceNotification
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
     * @return DeviceNotification
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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return DeviceNotification
     */
    public function setStartTime($startTime)
    {
        $this->start_time = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return DeviceNotification
     */
    public function setEndTime($endTime)
    {
        $this->end_time = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Set repeatUnit
     *
     * @param integer $repeatUnit
     *
     * @return DeviceNotification
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
     * Set lastSentDate
     *
     * @param \DateTime $lastSentDate
     *
     * @return DeviceNotification
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
     * Set description
     *
     * @param string $description
     *
     * @return DeviceNotification
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->repeat_week_days = new \Doctrine\Common\Collections\ArrayCollection();
        $this->repeat_month_days = new \Doctrine\Common\Collections\ArrayCollection();
        $this->repeat_months = new \Doctrine\Common\Collections\ArrayCollection();
        $this->repeat_histories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set repeatSubunit
     *
     * @param integer $repeatSubunit
     *
     * @return DeviceNotification
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
     * @return DeviceNotification
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

    /**
     * Add repeatWeekDay
     *
     * @param \AssignmentsBundle\Entity\Notification\Repeat\RepeatWeekDay $repeatWeekDay
     *
     * @return DeviceNotification
     */
    public function addRepeatWeekDay(\AssignmentsBundle\Entity\Notification\Repeat\RepeatWeekDay $repeatWeekDay)
    {
        $repeatWeekDay->setNotification($this);
        $this->repeat_week_days->add($repeatWeekDay);

        return $this;
    }

    /**
     * Remove repeatWeekDay
     *
     * @param \AssignmentsBundle\Entity\Notification\Repeat\RepeatWeekDay $repeatWeekDay
     */
    public function removeRepeatWeekDay(\AssignmentsBundle\Entity\Notification\Repeat\RepeatWeekDay $repeatWeekDay)
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
     * @param \AssignmentsBundle\Entity\Notification\Repeat\RepeatMonthDay $repeatMonthDay
     *
     * @return DeviceNotification
     */
    public function addRepeatMonthDay(\AssignmentsBundle\Entity\Notification\Repeat\RepeatMonthDay $repeatMonthDay)
    {
        $repeatMonthDay->setNotification($this);
        $this->repeat_month_days->add($repeatMonthDay);

        return $this;
    }

    /**
     * Remove repeatMonthDay
     *
     * @param \AssignmentsBundle\Entity\Notification\Repeat\RepeatMonthDay $repeatMonthDay
     */
    public function removeRepeatMonthDay(\AssignmentsBundle\Entity\Notification\Repeat\RepeatMonthDay $repeatMonthDay)
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
     * @param \AssignmentsBundle\Entity\Notification\Repeat\RepeatMonth $repeatMonth
     *
     * @return DeviceNotification
     */
    public function addRepeatMonth(\AssignmentsBundle\Entity\Notification\Repeat\RepeatMonth $repeatMonth)
    {
        $repeatMonth->setNotification($this);
        $this->repeat_months->add($repeatMonth);

        return $this;
    }

    /**
     * Remove repeatMonth
     *
     * @param \AssignmentsBundle\Entity\Notification\Repeat\RepeatMonth $repeatMonth
     */
    public function removeRepeatMonth(\AssignmentsBundle\Entity\Notification\Repeat\RepeatMonth $repeatMonth)
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
     * Set depth
     *
     * @param integer $depth
     *
     * @return DeviceNotification
     */
    public function setDepth($depth)
    {
        $this->depth = $depth;

        return $this;
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
     * Add repeatHistory
     *
     * @param \AssignmentsBundle\Entity\Notification\Repeat\NotificationRepeatHistory $repeatHistory
     *
     * @return DeviceNotification
     */
    public function addRepeatHistory(\AssignmentsBundle\Entity\Notification\Repeat\NotificationRepeatHistory $repeatHistory)
    {
        $this->repeat_histories[] = $repeatHistory;

        return $this;
    }

    /**
     * Remove repeatHistory
     *
     * @param \AssignmentsBundle\Entity\Notification\Repeat\NotificationRepeatHistory $repeatHistory
     */
    public function removeRepeatHistory(\AssignmentsBundle\Entity\Notification\Repeat\NotificationRepeatHistory $repeatHistory)
    {
        $this->repeat_histories->removeElement($repeatHistory);
    }

    /**
     * Get repeatHistories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRepeatHistories()
    {
        return $this->repeat_histories;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return DeviceNotification
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
