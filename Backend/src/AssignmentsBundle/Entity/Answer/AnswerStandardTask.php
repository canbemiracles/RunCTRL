<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerStandardTask
 *
 * @ORM\Table(name="assignment_answer_standard_task")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerStandardTaskRepository")
 */
class AnswerStandardTask extends AbstractAnswer
{
    /**
     * @ORM\Column(name="answer", type="boolean")
     */
    protected $answer;

    /**
     * Set answer
     *
     * @param boolean $answer
     *
     * @return AnswerStandardTask
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return boolean
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}

