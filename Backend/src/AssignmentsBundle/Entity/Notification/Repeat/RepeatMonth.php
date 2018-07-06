<?php

namespace AssignmentsBundle\Entity\Notification\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * RepeatMonth
 *
 * @ORM\Table(name="device_notification_repeat_month")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\Repeat\RepeatMonthRepository")
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotification", inversedBy="repeat_months")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $notification;

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

    /**
     * Set notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotification $notification
     *
     * @return RepeatMonth
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
