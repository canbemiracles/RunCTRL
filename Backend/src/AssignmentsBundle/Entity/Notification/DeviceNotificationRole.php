<?php

namespace AssignmentsBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceNotificationRole
 *
 * @ORM\Table(name="device_notification_role")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\DeviceNotificationRoleRepository")
 */
class DeviceNotificationRole extends DeviceNotification
{
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Role\AbstractBranchStationRole", inversedBy="notifications")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE",nullable=true)
     */
    protected $role;

    /**
     * Set role
     *
     * @param \ApiBundle\Entity\Role\AbstractBranchStationRole $role
     *
     * @return DeviceNotificationRole
     */
    public function setRole(\ApiBundle\Entity\Role\AbstractBranchStationRole $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \ApiBundle\Entity\Role\AbstractBranchStationRole
     */
    public function getRole()
    {
        return $this->role;
    }

    public function getStartTimeTimeZone()
    {
        if(!empty($this->getRole()) && !empty($this->getStartTime())) {
            $offset = intval($this->getRole()->getBranchStation()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $start_time = (new \DateTime())->setTimestamp($this->getStartTime()->getTimestamp());
            return !empty($start_time) ? $start_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }

    public function getEndTimeTimeZone()
    {
        if(!empty($this->getRole()) && !empty($this->getEndTime())) {
            $offset = intval($this->getRole()->getBranchStation()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $end_time = (new \DateTime())->setTimestamp($this->getEndTime()->getTimestamp());
            return !empty($end_time) ? $end_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }
}
