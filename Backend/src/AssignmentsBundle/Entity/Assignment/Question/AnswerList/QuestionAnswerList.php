<?php

namespace AssignmentsBundle\Entity\Assignment\Question\AnswerList;

use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionAnswerList
 *
 * @ORM\Table(name="assignment_question_list")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Question\AnswerList\QuestionAnswerListRepository")
 */
class QuestionAnswerList extends AbstractAssignment
{
    /**
     * @ORM\OneToMany(targetEntity="QuestionPossibleAnswer", mappedBy="question", cascade={"persist"})
     */
    protected $possible_answers;

    public function __construct()
    {
        parent::__construct();
        $this->possible_answers = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add possibleAnswer
     *
     * @param \AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer $possibleAnswer
     *
     * @return QuestionAnswerList
     */
    public function addPossibleAnswer(\AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer $possibleAnswer)
    {
        $possibleAnswer->setQuestion($this);
        $this->possible_answers[] = $possibleAnswer;

        return $this;
    }

    /**
     * Remove possibleAnswer
     *
     * @param \AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer $possibleAnswer
     */
    public function removePossibleAnswer(\AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer $possibleAnswer)
    {
        $this->possible_answers->removeElement($possibleAnswer);
    }

    /**
     * Get possibleAnswers
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPossibleAnswers()
    {
        return $this->possible_answers;
    }

}
