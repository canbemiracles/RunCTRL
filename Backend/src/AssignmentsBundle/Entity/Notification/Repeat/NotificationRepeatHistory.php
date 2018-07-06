<?php

namespace AssignmentsBundle\Entity\Notification\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * NotificationRepeatHistory
 *
 * @ORM\Table(name="device_notification_repeat_history")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Notification\Repeat\NotificationRepeatHistoryRepository")
 */
class NotificationRepeatHistory
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotification", inversedBy="repeat_histories")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $notification;

    /**
     * @ORM\Column(name="parent", type="integer")
     */
    protected $parent;

    /**
     * @ORM\Column(name="date_added", type="datetime", nullable=true)
     */
    protected $date_added;


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
     * Set parent
     *
     * @param integer $parent
     *
     * @return NotificationRepeatHistory
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return NotificationRepeatHistory
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }

    /**
     * Set notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotification $notification
     *
     * @return NotificationRepeatHistory
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
