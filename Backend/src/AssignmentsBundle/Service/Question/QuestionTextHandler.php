<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 29.09.2017
 * Time: 13:41
 */

namespace AssignmentsBundle\Service\Question;


use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use AssignmentsBundle\Entity\Answer\AnswerQuestionText;
use AssignmentsBundle\Entity\Assignment\Question\QuestionText;
use AssignmentsBundle\Service\BaseAssignmentHandler;
use AssignmentsBundle\Service\Question\Interfaces\QuestionTextHandlerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuestionTextHandler extends BaseAssignmentHandler implements QuestionTextHandlerInterface
{

    public function handleAnswer(QuestionText $question, string $answer)
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
        $assignmentAnswer = new AnswerQuestionText();

        $assignmentAnswer->setAssignment($question);
        $assignmentAnswer->setAnswer($answer);

        $assignmentAnswer->setEmployee($employee);

        if($currentRoleEmployee != null) {
            $assignmentAnswer->setBranchShift($currentRoleEmployee->getBranchShift());
        }

        $em->persist($assignmentAnswer);
        $em->flush();

        return $assignmentAnswer;
    }

    public function snooze(QuestionText $questionText)
    {
        return $this->snoozeAssignment($questionText);
    }
}