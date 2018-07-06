<?php

namespace AssignmentsBundle\Entity\Notification\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepeatMonthDay
 *
 * @ORM\Table(name="device_notification_repeat_month_day")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\Repeat\RepeatMonthDayRepository")
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotification", inversedBy="repeat_month_days")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $notification;

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
     * Set notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotification $notification
     *
     * @return RepeatMonthDay
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
