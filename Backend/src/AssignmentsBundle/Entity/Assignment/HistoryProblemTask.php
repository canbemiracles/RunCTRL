<?php

namespace AssignmentsBundle\Entity\Assignment;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoryProblemTask
 *
 * @ORM\Table(name="assignment_history_problem_task")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\HistoryProblemTaskRepository")
 */
class HistoryProblemTask
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
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchShift", fetch="EAGER")
     * @ORM\JoinColumn(name="branch_shift_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $branch_shift;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $assignment;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;


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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return HistoryProblemTask
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     *
     * @return HistoryProblemTask
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
     * Set branchShift
     *
     * @param \ApiBundle\Entity\BranchShift $branchShift
     *
     * @return HistoryProblemTask
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
     * Set assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return HistoryProblemTask
     */
    public function setAssignment(\AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment = null)
    {
        $this->assignment = $assignment;

        return $this;
    }

    /**
     * Get assignment
     *
     * @return \AssignmentsBundle\Entity\Assignment\AbstractAssignment
     */
    public function getAssignment()
    {
        return $this->assignment;
    }
}
