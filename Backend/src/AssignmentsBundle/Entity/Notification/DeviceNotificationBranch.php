<?php

namespace AssignmentsBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceNotificationBranch
 *
 * @ORM\Table(name="device_notification_branch")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\DeviceNotificationBranchRepository")
 */
class DeviceNotificationBranch extends DeviceNotification
{
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Branch", inversedBy="notifications")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", onDelete="CASCADE",nullable=true)
     */
    protected $branch;

    /**
     * Set branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return DeviceNotificationBranch
     */
    public function setBranch(\ApiBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \ApiBundle\Entity\Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    public function getStartTimeTimeZone()
    {
        if(!empty($this->getBranch()) && !empty($this->getStartTime())) {
            $offset = intval($this->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $start_time = (new \DateTime())->setTimestamp($this->getStartTime()->getTimestamp());
            return !empty($start_time) ? $start_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }

    public function getEndTimeTimeZone()
    {
        if(!empty($this->getBranch()) && !empty($this->getEndTime())) {
            $offset = intval($this->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $end_time = (new \DateTime())->setTimestamp($this->getEndTime()->getTimestamp());
            return !empty($end_time) ? $end_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }
}
