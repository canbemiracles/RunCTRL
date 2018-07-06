<?php

namespace WebSocketsBundle\Entity\Notification;

use ApiBundle\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * AnnouncementNotification
 *
 * @ORM\Table(name="notification_announcement")
 * @ORM\Entity(repositoryClass="WebSocketsBundle\Repository\Notification\AnnouncementNotificationRepository")
 */
class AnnouncementNotification extends AbstractNotification
{
    use CreatedUpdatedTrait;

    /**
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Company", inversedBy="custom_notifications")
     * @ORM\JoinColumn(name="company_id")
     */
    protected $company;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\User\AbstractUser", inversedBy="announcement_notifications")
     * * @ORM\JoinTable(name="announcement_notifications_users",
     *      joinColumns={@ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $users;


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
     * Set title
     *
     * @param string $title
     *
     * @return AnnouncementNotification
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
     * @return AnnouncementNotification
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

    /**
     * Set company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return AnnouncementNotification
     */
    public function setCompany(\ApiBundle\Entity\Company $company = null)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * Get company
     *
     * @return \ApiBundle\Entity\Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * Add user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return AbstractNotification
     */
    public function addUser(\ApiBundle\Entity\User\AbstractUser $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     */
    public function removeUser(\ApiBundle\Entity\User\AbstractUser $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }
}
