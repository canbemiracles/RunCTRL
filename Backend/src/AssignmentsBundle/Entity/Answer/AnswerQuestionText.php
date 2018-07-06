<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerQuestionText
 *
 * @ORM\Table(name="assignment_answer_question_text")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerQuestionTextRepository")
 */
class AnswerQuestionText extends AbstractAnswer
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
     * @return AnswerQuestionText
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
