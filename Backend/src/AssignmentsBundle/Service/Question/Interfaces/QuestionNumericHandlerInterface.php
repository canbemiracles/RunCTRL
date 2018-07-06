<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 29.09.2017
 * Time: 14:22
 */

namespace AssignmentsBundle\Service\Question\Interfaces;


use AssignmentsBundle\Entity\Assignment\Question\QuestionNumeric;

interface QuestionNumericHandlerInterface
{
    public function handleAnswer(QuestionNumeric $question, $answer);
    public function snooze(QuestionNumeric $questionNumeric);
}