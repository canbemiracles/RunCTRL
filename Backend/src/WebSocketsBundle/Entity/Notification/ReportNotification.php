<?php

namespace WebSocketsBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * ReportNotification
 *
 * @ORM\Table(name="notification_report")
 * @ORM\Entity(repositoryClass="WebSocketsBundle\Repository\Notification\ReportNotificationRepository")
 */
class ReportNotification extends AbstractNotification
{
    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Report\AbstractReport", inversedBy="notifications")
     * @ORM\JoinColumn(name="report_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $report;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\AbstractUser")
     * @ORM\JoinColumn(name="user_id")
     */
    protected $user;

    /**
     * Set report
     *
     * @param \ApiBundle\Entity\Report\AbstractReport $report
     *
     * @return ReportNotification
     */
    public function setReport(\ApiBundle\Entity\Report\AbstractReport $report = null)
    {
        $this->report = $report;

        return $this;
    }

    /**
     * Get report
     *
     * @return \ApiBundle\Entity\Report\AbstractReport
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return ReportNotification
     */
    public function setUser(\ApiBundle\Entity\User\AbstractUser $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \ApiBundle\Entity\User\AbstractUser
     */
    public function getUser()
    {
        return $this->user;
    }
}
