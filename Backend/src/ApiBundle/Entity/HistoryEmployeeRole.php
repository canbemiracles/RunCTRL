<?php

namespace ApiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryEmployeeRole
 *
 * @ORM\Table(name="history_employee_role")
 * @ORM\Entity(repositoryClass="ApiBundle\Repository\HistoryEmployeeRoleRepository")
 */
class HistoryEmployeeRole
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Employee", fetch="EAGER")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Role\AbstractBranchStationRole")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $role;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchShift", inversedBy="history_employee_roles", fetch="EAGER")
     * @ORM\JoinColumn(name="branch_shift_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_shift;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $date_start;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $date_end;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->date_start = new \DateTime(date('Y-m-d H:i:s'));
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
     * Set employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     *
     * @return HistoryEmployeeRole
     */
    public function setEmployee(\ApiBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \ApiBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return HistoryEmployeeRole
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return HistoryEmployeeRole
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branchShift
     *
     * @return HistoryEmployeeRole
     */
    public function setBranchShift(\ApiBundle\Entity\BranchShift $branchShift = null)
    {
        $this->branch_shift = $branchShift;

        return $this;
    }

    /**
     * Get branchShift
     *
     * @return \ApiBundle\Entity\BranchShift
     */
    public function getBranchShift()
    {
        return $this->branch_shift;
    }

    /**
     * Set role
     *
     * @param \ApiBundle\Entity\Role\AbstractBranchStationRole $role
     *
     * @return HistoryEmployeeRole
     */
    public function setRole(\ApiBundle\Entity\Role\AbstractBranchStationRole $role = null)
    {
        $this->role = $role;

        return $this;
    }

    /**
     * Get role
     *
     * @return \ApiBundle\Entity\Role\AbstractBranchStationRole
     */
    public function getRole()
    {
        return $this->role;
    }
}
