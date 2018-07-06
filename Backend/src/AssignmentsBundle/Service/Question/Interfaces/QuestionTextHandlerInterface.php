<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 29.09.2017
 * Time: 13:40
 */

namespace AssignmentsBundle\Service\Question\Interfaces;


use AssignmentsBundle\Entity\Assignment\Question\QuestionText;

interface QuestionTextHandlerInterface
{
    public function handleAnswer(QuestionText $question, string $answer);
    public function snooze(QuestionText $questionText);
}