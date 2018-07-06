<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerQuestionAnswerList
 *
 * @ORM\Table(name="assignment_question_answer_list")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerQuestionAnswerListRepository")
 */
class AnswerQuestionAnswerList extends AbstractAnswer
{
    /**
     * @ORM\OneToOne(targetEntity="AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer")
     */
    protected $answer;

    /**
     * Set answer
     *
     * @param \AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer $answer
     *
     * @return AnswerQuestionAnswerList
     */
    public function setAnswer(\AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer $answer = null)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return \AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer
     */
    public function getAnswer()
    {
        return $this->answer;
    }
}
