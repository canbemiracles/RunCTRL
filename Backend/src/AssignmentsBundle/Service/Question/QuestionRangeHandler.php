<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 29.09.2017
 * Time: 14:03
 */

namespace AssignmentsBundle\Service\Question;


use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use AssignmentsBundle\Entity\Answer\AnswerQuestionRange;
use AssignmentsBundle\Entity\Assignment\Question\QuestionRange;
use AssignmentsBundle\Service\BaseAssignmentHandler;
use AssignmentsBundle\Service\Question\Interfaces\QuestionRangeHandlerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuestionRangeHandler extends BaseAssignmentHandler implements QuestionRangeHandlerInterface
{

    public function handleAnswer(QuestionRange $question, $rangeX, $rangeY)
    {
        $em = $this->entityManager;

        /** @var $employee Employee*/
        $employee = null;
        $currentRoleEmployee = $this->historyEmployeeRoleRepository->getCurrentHistoryEmployeeRole($question->getRole());

        if($currentRoleEmployee != null) {
            $employee = $currentRoleEmployee->getEmployee();
        }

        //Checking if this assignment already answered
        if($this->isAlreadyAnswered($question))
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.cat_not_answer_more_one_time"));
        }

        //Checking if we got answer in time interval of this assignment
        if($this->isTimeout($question))
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.cat_not_answer_time_out"));
        }

        //Creating new answer entity
        $assignmentAnswer = new AnswerQuestionRange();

        $assignmentAnswer->setAssignment($question);

        $assignmentAnswer->setRangeX($rangeX);
        $assignmentAnswer->setRangeY($rangeY);

        $assignmentAnswer->setEmployee($employee);

        if($currentRoleEmployee != null) {
            $assignmentAnswer->setBranchShift($currentRoleEmployee->getBranchShift());
        }

        $em->persist($assignmentAnswer);
        $em->flush();

        return $assignmentAnswer;
    }

    public function snooze(QuestionRange $questionRange)
    {
        return $this->snoozeAssignment($questionRange);
    }
}