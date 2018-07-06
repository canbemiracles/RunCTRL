<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerCheckList
 *
 * @ORM\Table(name="assignment_answer_check_list")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerCheckListRepository")
 */
class AnswerCheckList extends AbstractAnswer
{
    /**
     * @ORM\OneToMany(targetEntity="AssignmentsBundle\Entity\Answer\AnswerCheckListDoneTasks", mappedBy="checklist")
     */
    protected $doneTasks;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->doneTasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add doneTask
     *
     * @param \AssignmentsBundle\Entity\Answer\AnswerCheckListDoneTasks $doneTask
     *
     * @return AnswerCheckList
     */
    public function addDoneTask(\AssignmentsBundle\Entity\Answer\AnswerCheckListDoneTasks $doneTask)
    {
        $this->doneTasks[] = $doneTask;

        return $this;
    }

    /**
     * Remove doneTask
     *
     * @param \AssignmentsBundle\Entity\Answer\AnswerCheckListDoneTasks $doneTask
     */
    public function removeDoneTask(\AssignmentsBundle\Entity\Answer\AnswerCheckListDoneTasks $doneTask)
    {
        $this->doneTasks->removeElement($doneTask);
    }

    /**
     * Get doneTasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDoneTasks()
    {
        return $this->doneTasks;
    }
}
