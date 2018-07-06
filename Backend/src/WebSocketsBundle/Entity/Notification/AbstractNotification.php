<?php

namespace WebSocketsBundle\Entity\Notification;

use ApiBundle\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractNotification
 *
 * @ORM\Table(name="notification")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({
 *     "report_notification" = "ReportNotification",
 *     "announcement_notification" = "AnnouncementNotification",
 *     "alert_notification" = "AlertNotification"
 * })
 * @ORM\Entity(repositoryClass="WebSocketsBundle\Repository\Notification\AbstractNotificationRepository")
 */
abstract class AbstractNotification
{
    use CreatedUpdatedTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="is_read", type="boolean")
     */
    protected $read = 0;


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
     * Set read
     *
     * @param boolean $read
     *
     * @return AbstractNotification
     */
    public function setRead($read)
    {
        $this->read = $read;

        return $this;
    }

    /**
     * Get read
     *
     * @return boolean
     */
    public function getRead()
    {
        return $this->read;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }
}
