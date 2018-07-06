<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 29.09.2017
 * Time: 14:35
 */

namespace AssignmentsBundle\Service\Question\Interfaces;


use AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList;

interface QuestionAnswerListHandlerInterface
{
    public function handleAnswer(QuestionAnswerList $question, string $answer);
    public function snooze(QuestionAnswerList $questionAnswerList);
}