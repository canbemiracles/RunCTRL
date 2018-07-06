<?php

namespace AssignmentsBundle\Entity\Answer;

use ApiBundle\Entity\Traits\CreatedUpdatedTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * AbstractAnswer
 *
 * @ORM\Table(name="assignment_answer")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AbstractAnswerRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap(
 *     {
 *      "checklist" = "AnswerCheckList",
 *      "answer_list" = "AnswerQuestionAnswerList",
 *      "range" = "AnswerQuestionRange",
 *      "text" = "AnswerQuestionText",
 *      "yes_no" = "AnswerQuestionYesNo",
 *      "numeric" = "AnswerQuestionNumeric",
 *      "task" = "AnswerStandardTask"
 *     }
 * )
 */
abstract class AbstractAnswer
{
    use CreatedUpdatedTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment", inversedBy="answers")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $assignment;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\Employee", inversedBy="assignmentAnswers")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="ApiBundle\Entity\BranchShift", inversedBy="assignmentAnswers")
     * @ORM\JoinColumn(name="branch_shift_id", referencedColumnName="id", nullable=true, onDelete="CASCADE")
     */
    protected $branch_shift;

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
     * Set assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return AbstractAnswer
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

    /**
     * Set employee
     *
     * @param \ApiBundle\Entity\Employee $employee
     *
     * @return AbstractAnswer
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
     * @return AbstractAnswer
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
}
