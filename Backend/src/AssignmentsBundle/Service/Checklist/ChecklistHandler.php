<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 30.09.2017
 * Time: 16:49
 */

namespace AssignmentsBundle\Service\Checklist;


use ApiBundle\Entity\Employee;
use ApiBundle\Entity\HistoryEmployeeRole;
use AssignmentsBundle\Entity\Answer\AnswerCheckList;
use AssignmentsBundle\Entity\Answer\AnswerCheckListDoneTasks;
use AssignmentsBundle\Entity\Assignment\Checklist\Checklist;
use AssignmentsBundle\Entity\Assignment\Checklist\Tasks;
use AssignmentsBundle\Service\BaseAssignmentHandler;
use AssignmentsBundle\Service\Checklist\Interfaces\ChecklistHandlerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ChecklistHandler extends BaseAssignmentHandler implements ChecklistHandlerInterface
{

    public function handleAnswer(Checklist $checklist, $answer)
    {
        $em = $this->entityManager;
        $current_time = new \DateTime();

        /** @var $employee Employee*/
        $employee = null;
        $currentRoleEmployee = $this->historyEmployeeRoleRepository->getCurrentHistoryEmployeeRole($checklist->getRole());

        if($currentRoleEmployee != null) {
            $employee = $currentRoleEmployee->getEmployee();
        }

        //Checking if we got answer in time interval of this assignment
        if($this->isTimeout($checklist))
        {
            throw new BadRequestHttpException($this->translator->trans("assignments.cat_not_answer_time_out"));
        }

        /** @var $task Tasks*/
        $task = $em->getRepository('AssignmentsBundle:Assignment\Checklist\Tasks')->find($answer);

        if($task == null || !$checklist->getTasks()->contains($task))
        {
            throw new BadRequestHttpException('Task is not found');
        }

        $lastAnswer = $this->answerRepository->getLastAnswer($checklist);

        $assignmentAnswer = null;

        if($lastAnswer != null) {
            //Check if we already have an answer today for this assignment, the case is if someone answered and pressed snooze at the same time.
            if ($lastAnswer->getCreated()->format('Y-m-d') == $current_time->format('Y-m-d')) {
                $assignmentAnswer = $lastAnswer;
            }

            //Checking if this assignment already answered
            if($this->isChecklistFullyAnswered($lastAnswer, $checklist))
            {
                throw new BadRequestHttpException($this->translator->trans("assignments.checklist_fully_answered"));
            }
            if($this->isTaskDone($task, $lastAnswer))
            {
                throw new BadRequestHttpException($this->translator->trans("assignments.already_done"));
            }

        }
        if($assignmentAnswer == null){
            //Creating new answer entity
            $assignmentAnswer = new AnswerCheckList();
            $assignmentAnswer->setAssignment($checklist);
            $assignmentAnswer->setEmployee($employee);

            if($currentRoleEmployee != null) {
                $assignmentAnswer->setBranchShift($currentRoleEmployee->getBranchShift());
            }
        }

        $em->persist($assignmentAnswer);

        $doneTask = new AnswerCheckListDoneTasks();
        $doneTask->setChecklist($assignmentAnswer);
        $doneTask->setTask($task);

        $em->persist($doneTask);

        $em->flush();

        return $assignmentAnswer;
    }

    public function snooze(Checklist $checklist)
    {
        return $this->snoozeAssignment($checklist);
    }

    public function isChecklistFullyAnswered(AnswerCheckList $answer, Checklist $checklist)
    {
        $doneTasks = $answer->getDoneTasks();

        if($doneTasks == null) {
            return false;
        }

        return $checklist->getTasks()->count() == $answer->getDoneTasks()->count();
    }

    public function isTaskDone(Tasks $task, AnswerCheckList $checklist)
    {
        foreach($checklist->getDoneTasks() as $doneTask)
        {
            /** @var $doneTask AnswerCheckListDoneTasks*/
            if($doneTask->getTask() === $task) {
                return true;
            }
        }
        return false;
    }
}