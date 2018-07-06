<?php

namespace AssignmentsBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceNotificationMessage
 *
 * @ORM\Table(name="device_notification_message")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\DeviceNotificationMessageRepository")
 */
class DeviceNotificationMessage extends DeviceNotification
{
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Employee", inversedBy="messages")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id", onDelete="CASCADE",nullable=true)
     */
    protected $employee;

    /**
     * Set employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     *
     * @return DeviceNotificationMessage
     */
    public function setEmployee(\ApiBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \ApiBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    public function getEmployeeId()
    {
        return $this->getEmployee()->getId();
    }

    public function getStartTimeTimeZone()
    {
        if(!empty($this->getEmployee()) && !empty($this->getStartTime())) {
            $offset = intval($this->getEmployee()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $start_time = (new \DateTime())->setTimestamp($this->getStartTime()->getTimestamp());
            return !empty($start_time) ? $start_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }

    public function getEndTimeTimeZone()
    {
        if(!empty($this->getEmployee()) && !empty($this->getEndTime())) {
            $offset = intval($this->getEmployee()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $end_time = (new \DateTime())->setTimestamp($this->getEndTime()->getTimestamp());
            return !empty($end_time) ? $end_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }
}
