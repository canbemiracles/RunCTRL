<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Branch
 *
 * @ORM\Table(name="branch")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\BranchRepository")
 */
class Branch
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Company", inversedBy="branches")
     * @ORM\JoinColumn(name="company_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $company;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\User\Supervisor", inversedBy="branches")
     * @ORM\JoinColumn(name="supervisor_id", referencedColumnName="id", onDelete="CASCADE")

     */
    protected $supervisor;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\User\BranchManager", mappedBy="branch")
     */
    protected $branch_manager;

    /**
     * @ORM\OneToOne(targetEntity="ApiBundle\Entity\GeographicalArea", mappedBy="branch", cascade={"persist", "remove"}, fetch="EAGER")
     * @ORM\JoinColumn(name="geographical_area_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $geographical_area;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\BranchShift", mappedBy="branch")
     */
    protected $shifts;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\BranchStation", mappedBy="branch")
     */
    protected $stations;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\User\CoManager", mappedBy="branch")
     */
    protected $co_managers;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Employee", mappedBy="branches")
     */
    protected $employees;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\ShiftDay", mappedBy="branches")
     */
    protected $shift_days;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotificationBranch", mappedBy="branch")
     */
    protected $notifications;

    /**
     * @ORM\Column(type="string", name="police_phone", nullable=true)
     */
    protected $police_phone;

    /**
     * @ORM\Column(type="string", name="fire_phone", nullable=true)
     */
    protected $fire_phone;

    /**
     * @ORM\Column(type="string", name="ambulance_phone", nullable=true)
     */
    protected $ambulance_phone;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->shifts = new \Doctrine\Common\Collections\ArrayCollection();
        $this->stations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->co_managers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->shift_days = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set company
     *
     * @param \ApiBundle\Entity\Company $company
     *
     * @return Branch
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
     * Set geographicalArea
     *
     * @param \ApiBundle\Entity\GeographicalArea $geographicalArea
     *
     * @return Branch
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
     * Set branchManager
     *
     * @param \ApiBundle\Entity\User\BranchManager $branchManager
     *
     * @return Branch
     */
    public function setBranchManager(\ApiBundle\Entity\User\BranchManager $branchManager = null)
    {
        $this->branch_manager = $branchManager;

        return $this;
    }

    /**
     * Get branchManager
     *
     * @return \ApiBundle\Entity\User\BranchManager
     */
    public function getBranchManager()
    {
        return $this->branch_manager;
    }


    /**
     * Add shift
     *
     * @param \ApiBundle\Entity\BranchShift $shift
     *
     * @return Branch
     */
    public function addShift(\ApiBundle\Entity\BranchShift $shift)
    {
        $this->shifts[] = $shift;

        return $this;
    }

    /**
     * Remove shift
     *
     * @param \ApiBundle\Entity\BranchShift $shift
     */
    public function removeShift(\ApiBundle\Entity\BranchShift $shift)
    {
        $this->shifts->removeElement($shift);
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShifts()
    {
        return $this->shifts;
    }

    /**
     * Add station
     *
     * @param \ApiBundle\Entity\BranchStation $station
     *
     * @return Branch
     */
    public function addStation(\ApiBundle\Entity\BranchStation $station)
    {
        $this->stations[] = $station;

        return $this;
    }

    /**
     * Remove station
     *
     * @param \ApiBundle\Entity\BranchStation $station
     */
    public function removeStation(\ApiBundle\Entity\BranchStation $station)
    {
        $this->stations->removeElement($station);
    }

    /**
     * @return mixed
     */
    public function getStations()
    {
        return $this->stations;
    }


    /**
     * Add employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     *
     * @return Branch
     */
    public function addEmployee(\ApiBundle\Entity\Employee $employee)
    {
        $this->employees[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param \ApiBundle\Entity\Employee $employee
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
     * Add coManager
     *
     * @param \ApiBundle\Entity\User\CoManager $coManager
     *
     * @return Branch
     */
    public function addCoManager(\ApiBundle\Entity\User\CoManager $coManager)
    {
        $this->co_managers[] = $coManager;

        return $this;
    }

    /**
     * Remove coManager
     *
     * @param \ApiBundle\Entity\User\CoManager $coManager
     */
    public function removeCoManager(\ApiBundle\Entity\User\CoManager $coManager)
    {
        $this->co_managers->removeElement($coManager);
    }

    /**
     * Get coManagers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoManagers()
    {
        return $this->co_managers;
    }

    /**
     * Add shiftDay
     *
     * @param \ApiBundle\Entity\ShiftDay $shiftDay
     *
     * @return Branch
     */
    public function addShiftDay(\ApiBundle\Entity\ShiftDay $shiftDay)
    {
        $this->shift_days[] = $shiftDay;

        return $this;
    }

    /**
     * Remove shiftDay
     *
     * @param \ApiBundle\Entity\ShiftDay $shiftDay
     */
    public function removeShiftDay(\ApiBundle\Entity\ShiftDay $shiftDay)
    {
        $this->shift_days->removeElement($shiftDay);
    }

    /**
     * Get shiftDays
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getShiftDays()
    {
        return $this->shift_days;
    }

    /**
     * Add notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationBranch $notification
     *
     * @return Branch
     */
    public function addNotification(\AssignmentsBundle\Entity\Notification\DeviceNotificationBranch $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationBranch $notification
     */
    public function removeNotification(\AssignmentsBundle\Entity\Notification\DeviceNotificationBranch $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }


    /**
     * Get company id
     *
     * @return integer
     */
    public function getCompanyId()
    {
        return $this->getCompany()->getId();
    }

    /**
     * Set supervisor
     *
     * @param \ApiBundle\Entity\User\Supervisor $supervisor
     *
     * @return Branch
     */
    public function setSupervisor(\ApiBundle\Entity\User\Supervisor $supervisor = null)
    {
        $this->supervisor = $supervisor;

        return $this;
    }

    /**
     * Get supervisor
     *
     * @return \ApiBundle\Entity\User\Supervisor
     */
    public function getSupervisor()
    {
        return $this->supervisor;
    }

    /**
     * Set policePhone
     *
     * @param string $policePhone
     *
     * @return Branch
     */
    public function setPolicePhone($policePhone)
    {
        $this->police_phone = $policePhone;

        return $this;
    }

    /**
     * Get policePhone
     *
     * @return string
     */
    public function getPolicePhone()
    {
        return $this->police_phone;
    }

    /**
     * Set firePhone
     *
     * @param string $firePhone
     *
     * @return Branch
     */
    public function setFirePhone($firePhone)
    {
        $this->fire_phone = $firePhone;

        return $this;
    }

    /**
     * Get firePhone
     *
     * @return string
     */
    public function getFirePhone()
    {
        return $this->fire_phone;
    }

    /**
     * Set ambulancePhone
     *
     * @param string $ambulancePhone
     *
     * @return Branch
     */
    public function setAmbulancePhone($ambulancePhone)
    {
        $this->ambulance_phone = $ambulancePhone;

        return $this;
    }

    /**
     * Get ambulancePhone
     *
     * @return string
     */
    public function getAmbulancePhone()
    {
        return $this->ambulance_phone;
    }
}
