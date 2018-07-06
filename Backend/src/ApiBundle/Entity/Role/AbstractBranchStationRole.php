<?php

namespace ApiBundle\Entity\Role;

use ApiBundle\Entity\BranchStation;
use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractBranchStationRole
 *
 * @ORM\Table(name="branch_station_role")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"OriginRole" = "BranchStationOriginRole", "TempRole" = "BranchStationTempRole",})
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\Role\AbstractBranchStationRoleRepository")
 */
abstract class AbstractBranchStationRole
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchStation", inversedBy="roles")
     * @ORM\JoinColumn(name="branch_station_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_station;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $role;

    /**
     * @ORM\OneToMany(targetEntity="ApiBundle\Entity\HistoryEmployeeRole", mappedBy="history_role")
     */
    protected $history_employees;

    /**
     * @ORM\ManyToMany(targetEntity="ApiBundle\Entity\Employee", mappedBy="roles")
     */
    protected $employees;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment", mappedBy="role")
     */
    protected $assignments;

    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Notification\DeviceNotificationMessage", mappedBy="role")
     */
    protected $notifications;

    /**
     * @ORM\Column(type="string", length=6)
     */
    protected $color;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->history_employees = new \Doctrine\Common\Collections\ArrayCollection();
        $this->assignments = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set role
     *
     * @param string $role
     *
     * @return AbstractBranchStationRole
     */
    public function setRole($role)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set branchStation
     *
     * @param \ApiBundle\Entity\BranchStation $branchStation
     *
     * @return AbstractBranchStationRole
     */
    public function setBranchStation(\ApiBundle\Entity\BranchStation $branchStation = null)
    {
        $this->branch_station = $branchStation;

        return $this;
    }

    /**
     * Get branchStation
     *
     * @return \ApiBundle\Entity\BranchStation
     */
    public function getBranchStation()
    {
        return $this->branch_station;
    }

    /**
     * Add employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     *
     * @return AbstractBranchStationRole
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
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * Add assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return AbstractBranchStationRole
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

    /**
     * Add notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $notification
     *
     * @return AbstractBranchStationRole
     */
    public function addNotification(\AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $notification
     */
    public function removeNotification(\AssignmentsBundle\Entity\Notification\DeviceNotificationMessage $notification)
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
     * @return int
     */
    public function getBranchStationId()
    {
        return $this->getBranchStation()->getId();
    }

    /**
     * @return int
     */
    public function getBranchId()
    {
        return $this->getBranchStation()->getBranch()->getId();
    }

    /**
     * @return string
    */
    public function getBranchStationName()
    {
        return $this->getBranchStation()->getName();
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return AbstractBranchStationRole
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Add historyEmployee
     *
     * @param \ApiBundle\Entity\HistoryEmployeeRole $historyEmployee
     *
     * @return AbstractBranchStationRole
     */
    public function addHistoryEmployee(\ApiBundle\Entity\HistoryEmployeeRole $historyEmployee)
    {
        $this->history_employees[] = $historyEmployee;

        return $this;
    }

    /**
     * Remove historyEmployee
     *
     * @param \ApiBundle\Entity\HistoryEmployeeRole $historyEmployee
     */
    public function removeHistoryEmployee(\ApiBundle\Entity\HistoryEmployeeRole $historyEmployee)
    {
        $this->history_employees->removeElement($historyEmployee);
    }

    /**
     * Get historyEmployees
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getHistoryEmployees()
    {
        return $this->history_employees;
    }
}
