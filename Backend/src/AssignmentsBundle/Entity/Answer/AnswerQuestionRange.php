<?php

namespace AssignmentsBundle\Entity\Answer;

use Doctrine\ORM\Mapping as ORM;

/**
 * AnswerQuestionRange
 *
 * @ORM\Table(name="assignment_answer_question_range")
 * @ORM\Entity(repositoryClass="AssignmentsBundle\Repository\Answer\AnswerQuestionRangeRepository")
 */
class AnswerQuestionRange extends AbstractAnswer
{
    /**
     * @ORM\Column(name="range_x", type="integer")
     */
    protected $range_x;

    /**
     * @ORM\Column(name="range_y", type="integer")
     */
    protected $range_y;

    /**
     * Set rangeX
     *
     * @param integer $rangeX
     *
     * @return AnswerQuestionRange
     */
    public function setRangeX($rangeX)
    {
        $this->range_x = $rangeX;

        return $this;
    }

    /**
     * Get rangeX
     *
     * @return integer
     */
    public function getRangeX()
    {
        return $this->range_x;
    }

    /**
     * Set rangeY
     *
     * @param integer $rangeY
     *
     * @return AnswerQuestionRange
     */
    public function setRangeY($rangeY)
    {
        $this->range_y = $rangeY;

        return $this;
    }

    /**
     * Get rangeY
     *
     * @return integer
     */
    public function getRangeY()
    {
        return $this->range_y;
    }
}
