<?php

namespace AssignmentsBundle\Entity\Assignment\Checklist;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tasks
 *
 * @ORM\Table(name="assignment_checklist_tasks")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Checklist\TasksRepository")
 */
class Tasks
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Checklist", inversedBy="tasks")
     * @ORM\JoinColumn(name="checklist_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $checklist;

    /**
     * @ORM\Column(name="task", type="string")
     */
    protected $task;

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
     * Set task
     *
     * @param string $task
     *
     * @return Tasks
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set checklist
     *
     * @param \AssignmentsBundle\Entity\Assignment\Checklist\Checklist $checklist
     *
     * @return Tasks
     */
    public function setChecklist(\AssignmentsBundle\Entity\Assignment\Checklist\Checklist $checklist = null)
    {
        $this->checklist = $checklist;

        return $this;
    }

    /**
     * Get checklist
     *
     * @return \AssignmentsBundle\Entity\Assignment\Checklist\Checklist
     */
    public function getChecklist()
    {
        return $this->checklist;
    }
}
