<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BranchStation
 *
 * @ORM\Table(name="branch_station")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\BranchStationRepository")
 */
class BranchStation
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Branch", inversedBy="stations")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Report\CashierReport", mappedBy="branch_station")
     */
    protected $cashier_reports;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Report\CommodityReport", mappedBy="branch_station")
     */
    protected $commodity_reports;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Report\ProblemReport", mappedBy="branch_station")
     */
    protected $problem_reports;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Role\BranchStationOriginRole", mappedBy="branch_station")
     */
    protected $origin_roles;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Role\BranchStationTempRole", mappedBy="branch_station")
     */
    protected $temp_roles;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\Role\AbstractBranchStationRole", mappedBy="branch_station")
     */
    protected $roles;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\User\Device\Device", mappedBy="station", fetch="EAGER")
     */
    protected $devices;

    /**
     * @ORM\Column(name="pin", type="integer", length=4, nullable=true)
     */
    protected $pin = null;

    /**
     * @ORM\Column(name="pin_expire", type="datetime", nullable=true)
     */
    protected $pin_expire = null;

    /**
     * @ORM\Column(name="device_code", type="string", nullable=true)
     */
    protected $device_code = null;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotificationStation", mappedBy="station")
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment", mappedBy="station")
     */
    protected $assignments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cashier_reports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->commodity_reports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->problem_reports = new \Doctrine\Common\Collections\ArrayCollection();
        $this->devices = new \Doctrine\Common\Collections\ArrayCollection();
        $this->origin_roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->temp_roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->notifications = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return BranchStation
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
     * Set branch
     *
     * @param \ApiBundle\Entity\Branch $branch
     *
     * @return BranchStation
     */
    public function setBranch(\ApiBundle\Entity\Branch $branch = null)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch
     *
     * @return \ApiBundle\Entity\Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Add cashierReport
     *
     * @param \ApiBundle\Entity\Report\CashierReport $cashierReport
     *
     * @return BranchStation
     */
    public function addCashierReport(\ApiBundle\Entity\Report\CashierReport $cashierReport)
    {
        $this->cashier_reports[] = $cashierReport;

        return $this;
    }

    /**
     * Remove cashierReport
     *
     * @param \ApiBundle\Entity\Report\CashierReport $cashierReport
     */
    public function removeCashierReport(\ApiBundle\Entity\Report\CashierReport $cashierReport)
    {
        $this->cashier_reports->removeElement($cashierReport);
    }

    /**
     * Get cashierReports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCashierReports()
    {
        return $this->cashier_reports;
    }

    /**
     * Add commodityReport
     *
     * @param \ApiBundle\Entity\Report\CommodityReport $commodityReport
     *
     * @return BranchStation
     */
    public function addCommodityReport(\ApiBundle\Entity\Report\CommodityReport $commodityReport)
    {
        $this->commodity_reports[] = $commodityReport;

        return $this;
    }

    /**
     * Remove commodityReport
     *
     * @param \ApiBundle\Entity\Report\CommodityReport $commodityReport
     */
    public function removeCommodityReport(\ApiBundle\Entity\Report\CommodityReport $commodityReport)
    {
        $this->commodity_reports->removeElement($commodityReport);
    }

    /**
     * Get commodityReports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommodityReports()
    {
        return $this->commodity_reports;
    }

    /**
     * Add problemReport
     *
     * @param \ApiBundle\Entity\Report\ProblemReport $problemReport
     *
     * @return BranchStation
     */
    public function addProblemReport(\ApiBundle\Entity\Report\ProblemReport $problemReport)
    {
        $this->problem_reports[] = $problemReport;

        return $this;
    }

    /**
     * Remove problemReport
     *
     * @param \ApiBundle\Entity\Report\ProblemReport $problemReport
     */
    public function removeProblemReport(\ApiBundle\Entity\Report\ProblemReport $problemReport)
    {
        $this->problem_reports->removeElement($problemReport);
    }

    /**
     * Get problemReports
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProblemReports()
    {
        return $this->problem_reports;
    }

    /**
     * Set pin
     *
     * @param integer $pin
     *
     * @return BranchStation
     */
    public function setPin($pin)
    {
        $this->pin = $pin;

        return $this;
    }

    /**
     * Get pin
     *
     * @return integer
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * Set pinExpire
     *
     * @param \DateTime $pinExpire
     *
     * @return BranchStation
     */
    public function setPinExpire($pinExpire)
    {
        $this->pin_expire = $pinExpire;

        return $this;
    }

    /**
     * Get pinExpire
     *
     * @return \DateTime
     */
    public function getPinExpire()
    {
        return $this->pin_expire;
    }

    /**
     * Set deviceCode
     *
     * @param string $deviceCode
     *
     * @return BranchStation
     */
    public function setDeviceCode($deviceCode)
    {
        $this->device_code = $deviceCode;

        return $this;
    }

    /**
     * Get deviceCode
     *
     * @return string
     */
    public function getDeviceCode()
    {
        return $this->device_code;
    }

    /**
     * Add device
     *
     * @param \ApiBundle\Entity\User\Device\Device $device
     *
     * @return BranchStation
     */
    public function addDevice(\ApiBundle\Entity\User\Device\Device $device)
    {
        $this->devices[] = $device;

        return $this;
    }

    /**
     * Remove device
     *
     * @param \ApiBundle\Entity\User\Device\Device $device
     */
    public function removeDevice(\ApiBundle\Entity\User\Device\Device $device)
    {
        $this->devices->removeElement($device);
    }

    /**
     * Get devices
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * Add notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationStation $notification
     *
     * @return BranchStation
     */
    public function addNotification(\AssignmentsBundle\Entity\Notification\DeviceNotificationStation $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationStation $notification
     */
    public function removeNotification(\AssignmentsBundle\Entity\Notification\DeviceNotificationStation $notification)
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
     * Add role
     *
     * @param \ApiBundle\Entity\Role\BranchStationOriginRole $role
     *
     * @return BranchStation
     */
    public function addOriginRole(\ApiBundle\Entity\Role\BranchStationOriginRole $role)
    {
        $this->origin_roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \ApiBundle\Entity\Role\BranchStationOriginRole $role
     */
    public function removeOriginRole(\ApiBundle\Entity\Role\BranchStationOriginRole $role)
    {
        $this->origin_roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOriginRoles()
    {
        return $this->origin_roles;
    }

    /**
     * Add role
     *
     * @param \ApiBundle\Entity\Role\BranchStationTempRole $role
     *
     * @return BranchStation
     */
    public function addTempRole(\ApiBundle\Entity\Role\BranchStationTempRole $role)
    {
        $this->temp_roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \ApiBundle\Entity\Role\BranchStationTempRole $role
     */
    public function removeTempRole(\ApiBundle\Entity\Role\BranchStationTempRole $role)
    {
        $this->temp_roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTempRoles()
    {
        return $this->temp_roles;
    }

    /**
     * Add role
     *
     * @param \ApiBundle\Entity\Role\AbstractBranchStationRole $role
     *
     * @return BranchStation
     */
    public function addRole(\ApiBundle\Entity\Role\AbstractBranchStationRole $role)
    {
        $this->roles[] = $role;

        return $this;
    }

    /**
     * Remove role
     *
     * @param \ApiBundle\Entity\Role\AbstractBranchStationRole $role
     */
    public function removeRole(\ApiBundle\Entity\Role\AbstractBranchStationRole $role)
    {
        $this->roles->removeElement($role);
    }

    /**
     * Get roles
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return BranchStation
     */
    public function addAssignment(\AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment)
    {
        $this->assignments[] = $assignment;

        return $this;
    }

    /**
     * Remove assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     */
    public function removeAssignment(\AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment)
    {
        $this->assignments->removeElement($assignment);
    }

    /**
     * Get assignments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssignments()
    {
        return $this->assignments;
    }
}
