<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 29.09.2017
 * Time: 14:36
 */

namespace AssignmentsBundle\Service\Question;


use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use AssignmentsBundle\Entity\Answer\AnswerQuestionAnswerList;
use AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList;
use AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer;
use AssignmentsBundle\Service\BaseAssignmentHandler;
use AssignmentsBundle\Service\Question\Interfaces\QuestionAnswerListHandlerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class QuestionAnswerListHandler extends BaseAssignmentHandler implements QuestionAnswerListHandlerInterface
{

    public function handleAnswer(QuestionAnswerList $question, string $answer)
    {
        $em = $this->entityManager;

        /** @var $possible_answer QuestionPossibleAnswer */
        $possible_answer = $em->getRepository('AssignmentsBundle:Assignment\Question\AnswerList\QuestionPossibleAnswer')->find($answer);

        if($possible_answer == null)
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.answer_not_belong"));
        }

        if($possible_answer->getQuestion() !== $question)
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.answer_not_belong"));
        }

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
        $assignmentAnswer = new AnswerQuestionAnswerList();

        $assignmentAnswer->setAssignment($question);
        $assignmentAnswer->setAnswer($possible_answer);

        $assignmentAnswer->setEmployee($employee);


        if($currentRoleEmployee != null) {
            $assignmentAnswer->setBranchShift($currentRoleEmployee->getBranchShift());
        }

        $em->persist($assignmentAnswer);
        $em->flush();

        return $assignmentAnswer;
    }

    public function snooze(QuestionAnswerList $questionAnswerList)
    {
        return $this->snoozeAssignment($questionAnswerList);
    }
}