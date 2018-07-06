<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 21.09.2017
 * Time: 13:53
 */

namespace AssignmentsBundle\Service\Question\Interfaces;


use AssignmentsBundle\Entity\Assignment\Question\QuestionYesNo;

interface QuestionYesNoHandlerInterface
{
    public function handleAnswer(QuestionYesNo $question, bool $answer);
    public function snooze(QuestionYesNo $questionYesNo);
}