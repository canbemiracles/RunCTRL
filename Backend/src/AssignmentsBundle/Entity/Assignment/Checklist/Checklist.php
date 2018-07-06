<?php

namespace AssignmentsBundle\Entity\Assignment\Checklist;

use Doctrine\ORM\Mapping as ORM;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;

/**
 * Checklist
 *
 * @ORM\Table(name="assignment_checklist")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Checklist\ChecklistRepository")
 */
class Checklist extends AbstractAssignment
{

    /**
     * @ORM\OneToMany(targetEntity="Tasks", mappedBy="checklist", cascade={"persist"})
     */
    protected $tasks;

    /**
     * Add task
     *
     * @param \AssignmentsBundle\Entity\Assignment\Checklist\Tasks $task
     *
     * @return Checklist
     */
    public function addTask(\AssignmentsBundle\Entity\Assignment\Checklist\Tasks $task)
    {
        $task->setChecklist($this);
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Remove task
     *
     * @param \AssignmentsBundle\Entity\Assignment\Checklist\Tasks $task
     */
    public function removeTask(\AssignmentsBundle\Entity\Assignment\Checklist\Tasks $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }
}
