<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 21.09.2017
 * Time: 13:53
 */

namespace AssignmentsBundle\Service\Question;


use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Service\Report\ProblemReport;
use AssignmentsBundle\Entity\Answer\AnswerQuestionYesNo;
use AssignmentsBundle\Entity\Assignment\Question\QuestionYesNo;
use AssignmentsBundle\Service\BaseAssignmentHandler;
use AssignmentsBundle\Service\Question\Interfaces\QuestionYesNoHandlerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use WebSocketsBundle\Service\NotificationService;

/**
 * Class QuestionYesNoHandler
 *
 * Service for handling Assignments Question (YES|NO) answers.
 *
 * @package AssignmentsBundle\Service\Question
 */
class QuestionYesNoHandler extends BaseAssignmentHandler implements QuestionYesNoHandlerInterface
{


    public function __construct(EntityManager $entityManager, ProblemReport $problemReport, NotificationService $notificationService, TranslatorInterface $translator)
    {
        parent::__construct($entityManager, $problemReport, $notificationService, $translator);
    }

    /**
     *
     * Getting user answer, checking if everything is ok, inserting new answer to database.
     *
     * @param QuestionYesNo $question
     * @param bool $answer
     * @return AnswerQuestionYesNo|BadRequestHttpException
     */
    public function handleAnswer(QuestionYesNo $question, bool $answer)
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
        $assignmentAnswer = new AnswerQuestionYesNo();

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

    public function snooze(QuestionYesNo $questionYesNo)
    {
        return $this->snoozeAssignment($questionYesNo);
    }
}