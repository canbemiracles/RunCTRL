<?php

namespace ApiBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * Alert
 *
 * @ORM\Table(name="alert")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Notification\AlertRepository")
 */
class Alert
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
     * @ORM\Column(name="ip", type="string")
     */
    protected $ip;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $confirmed = false;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\AbstractUser")
     * @ORM\JoinColumn(name="user_id")
     */
    protected $user;


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
     * Set ip
     *
     * @param string $ip
     *
     * @return Alert
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
     * @return Alert
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
     * @return Alert
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
