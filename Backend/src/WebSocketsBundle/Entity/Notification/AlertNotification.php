<?php

namespace WebSocketsBundle\Entity\Notification;

use ApiBundle\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * AlertNotification
 *
 * @ORM\Table(name="notification_alert")
 * @ORM\Entity(repositoryClass="WebSocketsBundle\Repository\Notification\AlertNotificationRepository")
 */
class AlertNotification extends AbstractNotification
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\Column(name="title", type="string", nullable=true)
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\Column(name="ip", type="string")
     */
    protected $ip;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $confirmed = false;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\AbstractUser", inversedBy="alerts")
     * @ORM\JoinColumn(name="user_id")
     */
    protected $user;

    /**
     * Set ip
     *
     * @param string $ip
     *
     * @return AlertNotification
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set confirmed
     *
     * @param boolean $confirmed
     *
     * @return AlertNotification
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;

        return $this;
    }

    /**
     * Get confirmed
     *
     * @return boolean
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * Set user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return AlertNotification
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


    /**
     * Set title
     *
     * @param string $title
     *
     * @return AlertNotification
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return AlertNotification
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
