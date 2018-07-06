<?php

namespace AssignmentsBundle\Entity\Assignment\Question\AnswerList;

use Doctrine\ORM\Mapping as ORM;

/**
 * QuestionPossibleAnswer
 *
 * @ORM\Table(name="assignment_question_list_possible_answer")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Assignment\Question\AnswerList\QuestionPossibleAnswerRepository")
 */
class QuestionPossibleAnswer
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
     * @ORM\Column(name="answer", type="string")
     */
    protected $answer;

    /**
     * @ORM\ManyToOne(targetEntity="QuestionAnswerList", inversedBy="possible_answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id", onDelete="cascade")
     */
    protected $question;

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
     * Set answer
     *
     * @param string $answer
     *
     * @return QuestionPossibleAnswer
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

    /**
     * Set question
     *
     * @param \AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList $question
     *
     * @return QuestionPossibleAnswer
     */
    public function setQuestion(\AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList $question = null)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return \AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList
     */
    public function getQuestion()
    {
        return $this->question;
    }
}
