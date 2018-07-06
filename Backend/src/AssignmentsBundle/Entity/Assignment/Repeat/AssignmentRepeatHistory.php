<?php

namespace AssignmentsBundle\Entity\Assignment\Repeat;

use Doctrine\ORM\Mapping as ORM;

/**
 * AssignmentRepeatHistory
 *
 * @ORM\Table(name="assignment_repeat_assignment_repeat_history")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Repeat\AssignmentRepeatHistoryRepository")
 */
class AssignmentRepeatHistory
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Assignment\AbstractAssignment", inversedBy="repeat_histories")
     * @ORM\JoinColumn(name="assignment_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $assignment;

    /**
     * @ORM\Column(name="parent", type="integer")
    */
    protected $parent;

    /**
     * @ORM\Column(name="date_added", type="datetime", nullable=true)
     */
    protected $date_added;


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
     * Set parent
     *
     * @param integer $parent
     *
     * @return AssignmentRepeatHistory
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return integer
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set dateAdded
     *
     * @param \DateTime $dateAdded
     *
     * @return AssignmentRepeatHistory
     */
    public function setDateAdded($dateAdded)
    {
        $this->date_added = $dateAdded;

        return $this;
    }

    /**
     * Get dateAdded
     *
     * @return \DateTime
     */
    public function getDateAdded()
    {
        return $this->date_added;
    }


    /**
     * Set assignment
     *
     * @param \AssignmentsBundle\Entity\Assignment\AbstractAssignment $assignment
     *
     * @return AssignmentRepeatHistory
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
