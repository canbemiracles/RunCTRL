<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerQuestionYesNo
 *
 * @ORM\Table(name="assignment_answer_question_yes_no")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerQuestionYesNoRepository")
 */
class AnswerQuestionYesNo extends AbstractAnswer
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
     * @return AnswerQuestionYesNo
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
