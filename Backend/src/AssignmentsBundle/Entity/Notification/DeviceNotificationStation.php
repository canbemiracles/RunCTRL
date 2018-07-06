<?php

namespace AssignmentsBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeviceNotificationStation
 *
 * @ORM\Table(name="device_notification_station")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\DeviceNotificationStationRepository")
 */
class DeviceNotificationStation extends DeviceNotification
{
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchStation", inversedBy="notifications")
     * @ORM\JoinColumn(name="station_id", referencedColumnName="id", onDelete="CASCADE",nullable=true)
     */
    protected $station;

    /**
     * Set station
     *
     * @param \ApiBundle\Entity\BranchStation $station
     *
     * @return DeviceNotificationStation
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

    public function getStartTimeTimeZone()
    {
        if(!empty($this->getStation()) && !empty($this->getStartTime())) {
            $offset = intval($this->getStation()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $start_time = (new \DateTime())->setTimestamp($this->getStartTime()->getTimestamp());
            return !empty($start_time) ? $start_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }

    public function getEndTimeTimeZone()
    {
        if(!empty($this->getStation()) && !empty($this->getEndTime())) {
            $offset = intval($this->getStation()->getBranch()->getCompany()->getTimeZone()->getOffset());
            /** @var $snooze \DateTime*/
            $end_time = (new \DateTime())->setTimestamp($this->getEndTime()->getTimestamp());
            return !empty($end_time) ? $end_time->modify("{$offset} hour") : null;
        } else {
            return null;
        }
    }
}
