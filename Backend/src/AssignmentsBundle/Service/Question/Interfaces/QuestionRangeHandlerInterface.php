<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 29.09.2017
 * Time: 14:02
 */

namespace AssignmentsBundle\Service\Question\Interfaces;


use AssignmentsBundle\Entity\Assignment\Question\QuestionRange;

interface QuestionRangeHandlerInterface
{
    public function handleAnswer(QuestionRange $question, $rangeX, $rangeY);
    public function snooze(QuestionRange $questionRange);
}