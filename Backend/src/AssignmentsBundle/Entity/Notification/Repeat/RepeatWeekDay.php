<?php

namespace AssignmentsBundle\Entity\Notification\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepeatWeekDay
 *
 * @ORM\Table(name="device_notification_repeat_week_day")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\Repeat\RepeatWeekDayRepository")
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotification", inversedBy="repeat_week_days")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $notification;

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
     * Set notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotification $notification
     *
     * @return RepeatWeekDay
     */
    public function setNotification(\AssignmentsBundle\Entity\Notification\DeviceNotification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \AssignmentsBundle\Entity\Notification\DeviceNotification
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
