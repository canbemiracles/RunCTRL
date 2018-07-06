<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerQuestionNumeric
 *
 * @ORM\Table(name="assignment_answer_question_numeric")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerQuestionNumericRepository")
 */
class AnswerQuestionNumeric extends AbstractAnswer
{
    /**
     * @ORM\Column(name="answer", type="string")
     */
    protected $answer;

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return AnswerQuestionNumeric
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}

