<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Company
 *
 * @ORM\Table(name="company")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\CompanyRepository")
 */
class Company
{
    const PLAN_FREE       = 'free';
    const PLAN_PRO        = 'pro';
    const PLAN_ENTERPRISE = 'enterprise';

    const PLAN_FREE_PAY_MONTH   =   30;
    const PLAN_PRO_PAY_MONTH        = 70;
    const PLAN_ENTERPRISE_PAY_MONTH = 100;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\GeographicalArea", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="geographical_area_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $geographical_area;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    protected $week_start_on;

    /**
     * @ORM\Column(type="float", precision=2, nullable=true)
     */
    protected $overtime_hourly_rate;

    /**
     * @ORM\Column(type="float", precision=2, nullable=true)
     */
    protected $weekend_rate;

    /**
     * @ORM\Column(name="plan", type="string", nullable=true)
     */
    protected $plan = null;

    /**
     * @ORM\Column(name="plan_pay", type="float", precision=2, nullable=true)
     */
    protected $plan_pay = null;

    /**
     * @ORM\Column(name="plan_payed_until", type="datetime", nullable=true)
     */
    protected $planPayedUntil;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\TimeZone", inversedBy="companies")
     * @ORM\JoinColumn(name="time_zone_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $time_zone;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Currency", inversedBy="companies")
     * @ORM\JoinColumn(name="currency_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $currency;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Branch", mappedBy="company")
     */
    protected $branches;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\User\AbstractUser", mappedBy="company")
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\DateFormat", inversedBy="companies")
     * @ORM\JoinColumn(name="date_format_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $date_format;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\TimeFormat")
     * @ORM\JoinColumn(name="time_format_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $time_format;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Subcategory", inversedBy="companies")
     * @ORM\JoinColumn(name="subcategory_id", referencedColumnName="id", onDelete="SET NULL")
     */
    protected $subcategory;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Employee", mappedBy="company")
     */
    protected $employees;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Notification\CustomNotification", mappedBy="company")
     */
    protected $custom_notifications;

    /**
     * @ORM\OneToMany(targetEntity="WebSocketsBundle\Entity\Notification\AnnouncementNotification", mappedBy="company")
     */
    protected $announcement_notifications;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\ShiftDay", inversedBy="companies")
     * @ORM\JoinTable(name="companies_weekends")
     */
    protected $weekends;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->branches = new \Doctrine\Common\Collections\ArrayCollection();
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->custom_notifications = new \Doctrine\Common\Collections\ArrayCollection();
        $this->weekends = new \Doctrine\Common\Collections\ArrayCollection();
        $this->announcement_notifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set geographicalArea
     *
     * @param \ApiBundle\Entity\GeographicalArea $geographicalArea
     *
     * @return Company
     */
    public function setGeographicalArea(\ApiBundle\Entity\GeographicalArea $geographicalArea = null)
    {
        $this->geographical_area = $geographicalArea;

        return $this;
    }

    /**
     * Get geographicalArea
     *
     * @return \ApiBundle\Entity\GeographicalArea
     */
    public function getGeographicalArea()
    {
        return $this->geographical_area;
    }

    /**
     * Set timeZone
     *
     * @param \ApiBundle\Entity\TimeZone $timeZone
     *
     * @return Company
     */
    public function setTimeZone(\ApiBundle\Entity\TimeZone $timeZone = null)
    {
        $this->time_zone = $timeZone;

        return $this;
    }

    /**
     * Get timeZone
     *
     * @return \ApiBundle\Entity\TimeZone
     */
    public function getTimeZone()
    {
        return $this->time_zone;
    }

    /**
     * Set currency
     *
     * @param \ApiBundle\Entity\Currency $currency
     *
     * @return Company
     */
    public function setCurrency(\ApiBundle\Entity\Currency $currency = null)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get currency
     *
     * @return \ApiBundle\Entity\Currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Company
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set overtimeHourlyRate
     *
     * @param float $overtimeHourlyRate
     *
     * @return Company
     */
    public function setOvertimeHourlyRate($overtimeHourlyRate)
    {
        $this->overtime_hourly_rate = $overtimeHourlyRate;

        return $this;
    }

    /**
     * Get overtimeHourlyRate
     *
     * @return float
     */
    public function getOvertimeHourlyRate()
    {
        return $this->overtime_hourly_rate;
    }

    /**
     * Set weekendRate
     *
     * @param float $weekendRate
     *
     * @return Company
     */
    public function setWeekendRate($weekendRate)
    {
        $this->weekend_rate = $weekendRate;

        return $this;
    }

    /**
     * Get weekendRate
     *
     * @return float
     */
    public function getWeekendRate()
    {
        return $this->weekend_rate;
    }
    
    /**
     * Add branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return Company
     */
    public function addBranch(\ApiBundle\Entity\Branch $branch)
    {
        $this->branches[] = $branch;

        return $this;
    }

    /**
     * Remove branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     */
    public function removeBranch(\ApiBundle\Entity\Branch $branch)
    {
        $this->branches->removeElement($branch);
    }

    /**
     * Get branches
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBranches()
    {
        return $this->branches;
    }

    /**
     * Add user
     *
     * @param \ApiBundle\Entity\User\AbstractUser $user
     *
     * @return Company
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
     * Set weekStartOn
     *
     * @param integer $weekStartOn
     *
     * @return Company
     */
    public function setWeekStartOn($weekStartOn)
    {
        $this->week_start_on = $weekStartOn;

        return $this;
    }

    /**
     * Get weekStartOn
     *
     * @return integer
     */
    public function getWeekStartOn()
    {
        return $this->week_start_on;
    }

    /**
     * Set plan
     *
     * @param string $plan
     *
     * @return Company
     */
    public function setPlan($plan)
    {
        $this->plan = $plan;

        return $this;
    }

    /**
     * Get plan
     *
     * @return string
     */
    public function getPlan()
    {
        return $this->plan;
    }

    /**
     * Set planPayedUntil
     *
     * @param \DateTime $planPayedUntil
     *
     * @return Company
     */
    public function setPlanPayedUntil($planPayedUntil)
    {
        $this->planPayedUntil = $planPayedUntil;

        return $this;
    }

    /**
     * Get planPayedUntil
     *
     * @return \DateTime
     */
    public function getPlanPayedUntil()
    {
        return $this->planPayedUntil;
    }

    /**
     * Set dateFormat
     *
     * @param \ApiBundle\Entity\DateFormat $dateFormat
     *
     * @return Company
     */
    public function setDateFormat(\ApiBundle\Entity\DateFormat $dateFormat = null)
    {
        $this->date_format = $dateFormat;

        return $this;
    }

    /**
     * Get dateFormat
     *
     * @return \ApiBundle\Entity\DateFormat
     */
    public function getDateFormat()
    {
        return $this->date_format;
    }

    /**
     * Set subcategory
     *
     * @param \ApiBundle\Entity\Subcategory $subcategory
     *
     * @return Company
     */
    public function setSubcategory(\ApiBundle\Entity\Subcategory $subcategory = null)
    {
        $this->subcategory = $subcategory;

        return $this;
    }

    /**
     * Get subcategory
     *
     * @return \ApiBundle\Entity\Subcategory
     */
    public function getSubcategory()
    {
        return $this->subcategory;
    }

    /**
     * Add employee
     *
     * @param Employee $employee
     * @return Company
     *
     */
    public function addEmployee(\ApiBundle\Entity\Employee $employee)
    {
        $this->employees[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param Employee $employee
     */
    public function removeEmployee(\ApiBundle\Entity\Employee $employee)
    {
        $this->employees->removeElement($employee);
    }

    /**
     * Get employees
     *
     * @return mixed
     */
    public function getEmployees()
    {
        return $this->employees;
    }


    /**
     * Add customNotification
     *
     * @param \ApiBundle\Entity\Notification\CustomNotification $customNotification
     *
     * @return Company
     */
    public function addCustomNotification(\ApiBundle\Entity\Notification\CustomNotification $customNotification)
    {
        $this->custom_notifications[] = $customNotification;

        return $this;
    }

    /**
     * Remove customNotification
     *
     * @param \ApiBundle\Entity\Notification\CustomNotification $customNotification
     */
    public function removeCustomNotification(\ApiBundle\Entity\Notification\CustomNotification $customNotification)
    {
        $this->custom_notifications->removeElement($customNotification);
    }

    /**
     * Get customNotifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomNotifications()
    {
        return $this->custom_notifications;
    }

    /**
     * Set timeFormat
     *
     * @param \ApiBundle\Entity\TimeFormat $timeFormat
     *
     * @return Company
     */
    public function setTimeFormat(\ApiBundle\Entity\TimeFormat $timeFormat = null)
    {
        $this->time_format = $timeFormat;

        return $this;
    }

    /**
     * Get timeFormat
     *
     * @return \ApiBundle\Entity\TimeFormat
     */
    public function getTimeFormat()
    {
        return $this->time_format;
    }

    /**
     * Add weekend
     *
     * @param \ApiBundle\Entity\ShiftDay $weekend
     *
     * @return Company
     */
    public function addWeekend(\ApiBundle\Entity\ShiftDay $weekend)
    {
        $this->weekends[] = $weekend;

        return $this;
    }

    /**
     * Remove weekend
     *
     * @param \ApiBundle\Entity\ShiftDay $weekend
     */
    public function removeWeekend(\ApiBundle\Entity\ShiftDay $weekend)
    {
        $this->weekends->removeElement($weekend);
    }

    /**
     * Get weekends
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getWeekends()
    {
        return $this->weekends;
    }

    /**
     * Add announcementNotification
     *
     * @param \WebSocketsBundle\Entity\Notification\AnnouncementNotification $announcementNotification
     *
     * @return Company
     */
    public function addAnnouncementNotification(\WebSocketsBundle\Entity\Notification\AnnouncementNotification $announcementNotification)
    {
        $this->announcement_notifications[] = $announcementNotification;

        return $this;
    }

    /**
     * Remove announcementNotification
     *
     * @param \WebSocketsBundle\Entity\Notification\AnnouncementNotification $announcementNotification
     */
    public function removeAnnouncementNotification(\WebSocketsBundle\Entity\Notification\AnnouncementNotification $announcementNotification)
    {
        $this->announcement_notifications->removeElement($announcementNotification);
    }

    /**
     * Get announcementNotifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnnouncementNotifications()
    {
        return $this->announcement_notifications;
    }

    /**
     * Set planPay
     *
     * @param string $planPay
     *
     * @return Company
     */
    public function setPlanPay($planPay)
    {
        $this->plan_pay = $planPay;

        return $this;
    }

    /**
     * Get planPay
     *
     * @return string
     */
    public function getPlanPay()
    {
        return $this->plan_pay;
    }
}
