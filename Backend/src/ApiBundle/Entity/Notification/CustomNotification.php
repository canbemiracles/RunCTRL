<?php

namespace ApiBundle\Entity\Notification;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomNotification
 *
 * @ORM\Table(name="custom_notification")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Notification\CustomNotificationRepository")
 */
class CustomNotification
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
     * @ORM\Column(name="title", type="string")
     */
    protected $title;

    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\User\AbstractUser", inversedBy="custom_notifications")
     * * @ORM\JoinTable(name="custom_notifications_users",
     *      joinColumns={@ORM\JoinColumn(name="notification_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")}
     * )
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Company", inversedBy="custom_notifications")
     * @ORM\JoinColumn(name="company_id")
     */
    protected $company;


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
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return CustomNotification
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
     * @return CustomNotification
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
     * Add user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return CustomNotification
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

    /**
     * Set company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return CustomNotification
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
}
