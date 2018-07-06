<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 02.10.2017
 * Time: 15:25
 */

namespace AssignmentsBundle\Service\Task;


use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\ShiftDay;
use AssignmentsBundle\Entity\Answer\AnswerStandardTask;
use AssignmentsBundle\Entity\Assignment\StandardTask;
use AssignmentsBundle\Service\BaseAssignmentHandler;
use AssignmentsBundle\Service\Task\Interfaces\StandardTaskHandlerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StandardTaskHandler extends BaseAssignmentHandler implements StandardTaskHandlerInterface
{

    public function workingOnIt(StandardTask $standardTask)
    {
        $em = $this->entityManager;

        if($standardTask->getWorking()) {
            throw new BadRequestHttpException($this->translator->trans("assignments.already_working"));
        }

        //Checking if we got answer in time interval of this assignment
        if($this->isTimeout($standardTask))
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.task_time_out"));
        }

        $standardTask->setWorking(true);
        $standardTask->setLastTimeSendConfirmation(new \DateTime());

        $em->persist($standardTask);
        $em->flush();

        $this->snooze($standardTask,false);

        return $standardTask;
    }

    public function handleAnswer(StandardTask $standardTask, bool $answer)
    {
        $em = $this->entityManager;

        /** @var $employee Employee*/
        $employee = null;

        $currentRoleEmployee = $this->historyEmployeeRoleRepository->getCurrentHistoryEmployeeRole($standardTask->getRole());

        if($currentRoleEmployee != null) {
            $employee = $currentRoleEmployee->getEmployee();
        }

        //Checking if this assignment already answered
        if($this->isAlreadyAnswered($standardTask))
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.cat_not_answer_more_one_time"));
        }

        //Checking if we got answer in time interval of this assignment
        if($this->isTimeout($standardTask))
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.cat_not_answer_time_out"));
        }

        //Creating new answer entity
        $assignmentAnswer = new AnswerStandardTask();

        $assignmentAnswer->setAssignment($standardTask);
        $assignmentAnswer->setAnswer($answer);

        $assignmentAnswer->setEmployee($employee);

        if($currentRoleEmployee != null) {
            $assignmentAnswer->setBranchShift($currentRoleEmployee->getBranchShift());
        }

        $em->persist($assignmentAnswer);
        $em->flush();

        if(!$answer) {
            // Get the current day of the week number
            $day_of_week = date('N', strtotime(date('Y-m-d')));

            /** @var $shift_day ShiftDay */
            $shift_day = $this->entityManager->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

            if($shift_day === null) {
                return "ShiftDay not found";
            }

            /** @var $current_shift BranchShift */
            $current_shift = $this->entityManager->getRepository('ApiBundle:BranchShift')
                ->getCurrentShift([
                    'branch' => $standardTask->getRole()->getBranchStation()->getBranch()->getId(),
                    'filter_shift_day' => $shift_day->getId(),
                    'filter_time_open' => new \DateTime(),
                    'filter_time_close' => new \DateTime()]);

            if(empty($current_shift)) {
                throw new BadRequestHttpException($this->translator->trans("shift_management.no_open_shift"));
            }

            $this->problemReport->generateProblemReport(
                $standardTask->getRole()->getBranchStation(),
                $current_shift,
                'Employee failed standard task',
                'Employee notified that task "' . $standardTask->getTitle() . '" is not done.');
        }

        return $assignmentAnswer;
    }

    public function snooze(StandardTask $standardTask, bool $increaseSnoozeCount = true)
    {
        return $this->snoozeAssignment($standardTask, $increaseSnoozeCount);
    }
}