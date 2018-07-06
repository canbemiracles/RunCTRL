<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerCheckListTasks
 *
 * @ORM\Table(name="assignment_answer_check_list_done_tasks")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerCheckListDoneTasksRepository")
 */
class AnswerCheckListDoneTasks
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
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Answer\AnswerCheckList", inversedBy="doneTasks")
     * @ORM\JoinColumn(name="checklist_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $checklist;

    /**
     * @ORM\ManyToOne(targetEntity="AssignmentsBundle\Entity\Assignment\Checklist\Tasks")
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", onDelete="CASCADE")
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
     * Set checklist
     *
     * @param \AssignmentsBundle\Entity\Answer\AnswerCheckList $checklist
     *
     * @return AnswerCheckListDoneTasks
     */
    public function setChecklist(\AssignmentsBundle\Entity\Answer\AnswerCheckList $checklist = null)
    {
        $this->checklist = $checklist;

        return $this;
    }

    /**
     * Get checklist
     *
     * @return \AssignmentsBundle\Entity\Answer\AnswerCheckList
     */
    public function getChecklist()
    {
        return $this->checklist;
    }

    /**
     * Set task
     *
     * @param \AssignmentsBundle\Entity\Assignment\Checklist\Tasks $task
     *
     * @return AnswerCheckListDoneTasks
     */
    public function setTask(\AssignmentsBundle\Entity\Assignment\Checklist\Tasks $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \AssignmentsBundle\Entity\Assignment\Checklist\Tasks
     */
    public function getTask()
    {
        return $this->task;
    }
}
