<?php

namespace AssignmentsBundle\Service;

use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Entity\ShiftDay;
use ApiBundle\Repository\BranchShiftRepository;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use ApiBundle\Service\Report\ProblemReport;
use AssignmentsBundle\Entity\Answer\AbstractAnswer;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use AssignmentsBundle\Entity\Assignment\HistoryProblemTask;
use AssignmentsBundle\Repository\Answer\AbstractAnswerRepository;
use AssignmentsBundle\Service\Manager\AssignmentManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Translation\TranslatorInterface;
use WebSocketsBundle\Service\NotificationService;

abstract class BaseAssignmentHandler
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var AbstractAnswerRepository */
    protected $answerRepository;

    /** @var HistoryEmployeeRoleRepository */
    protected $historyEmployeeRoleRepository;

    /** @var ProblemReport */
    protected $problemReport;

    /** @var NotificationService */
    protected $notificationService;

    /** @var TranslatorInterface */
    protected $translator;

    public function __construct(EntityManager $em, ProblemReport $problemReport, NotificationService $notificationService, TranslatorInterface $translator)
    {
        $this->entityManager = $em;
        $this->problemReport = $problemReport;
        $this->notificationService = $notificationService;
        $this->answerRepository = $this->entityManager->getRepository('AssignmentsBundle:Answer\AbstractAnswer');
        $this->historyEmployeeRoleRepository = $this->entityManager->getRepository('ApiBundle:HistoryEmployeeRole');
        $this->translator = $translator;
    }

    /**
     * @param AbstractAssignment $assignment
     * @return bool
     */
    public function isAlreadyAnswered(AbstractAssignment $assignment)
    {
        /** @var $lastAnswer AbstractAnswer */
        $lastAnswer = $this->answerRepository->getLastAnswer($assignment);

        //There is now previous answers
        if($lastAnswer == null) {
            return false;
        }

        $current_date = new \DateTime();

        //Answer exists, checking if assignment is repeatable. Check if we already have an answer today for this assignment
        if($assignment->getRepeatUnit() == null || $lastAnswer->getCreated()->format('Y-m-d') == $current_date->format('Y-m-d')) {
            return true;
        }

        return false;
    }

    /**
     *
     * Checking if assignment is active by time interval.
     * @param AbstractAssignment $assignment
     * @return bool
     */
    public function isTimeout(AbstractAssignment $assignment)
    {
        $start_time = $assignment->getStartTime();
        $end_time = $assignment->getEndTime();
        $current_time = date('H:i:s');

        return $current_time > $start_time && $current_time < $end_time;
    }

    /**
     * @param AbstractAssignment $assignment
     * @param bool $increaseSnoozeCount assignment_answer_question_numeric
     * @return AbstractAssignment|JsonResponse
     */
    public function snoozeAssignment(AbstractAssignment $assignment, bool $increaseSnoozeCount = true)
    {
        if($assignment->getSnoozeTime() == null)
        {
            throw new BadRequestHttpException("This assignment doesn't have snooze_time");
        }
        $current_time = new \DateTime();

        /** @var $lastAnswer AbstractAnswer */
        $lastAnswer = $this->answerRepository->getLastAnswer($assignment);

        if($lastAnswer != null && $lastAnswer->getCreated()->format('Y-xm-d') == $current_time->format('Y-m-d')) {
            //Check if we already have an answer today for this assignment, the case is if someone answered and pressed snooze at the same time.
            return new JsonResponse(["error" => $this->translator->trans("assignments.already_answered")]);
        }

        $currentSnoozeCount = $assignment->getSnoozeCount();

        //Checking if assignment can be snoozed
        if($currentSnoozeCount == $assignment->getSnoozeMax())
        {
            // Get the current day of the week number
            $day_of_week = date('N', strtotime(date('Y-m-d')));

            /** @var $shift_day ShiftDay */
            $shift_day = $this->entityManager->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

            if($shift_day === null) {
                return new JsonResponse(["error" => $this->translator->trans("shift_management.shift_day_not_found")]);
            }

            /** @var $repository_shift BranchShiftRepository*/
            $repository_shift = $this->entityManager->getRepository('ApiBundle:BranchShift');

            /** @var $current_shift BranchShift */
            $current_shift = $repository_shift->getCurrentShift([
                    'branch' => $assignment->getRole()->getBranchStation()->getBranch()->getId(),
                    'filter_shift_day' => $shift_day->getId(),
                    'filter_time_open' => $current_time,
                    'filter_time_close' => $current_time]);

            $report = $this->problemReport->generateProblemReport(
                $assignment->getRole()->getBranchStation(),
                $current_shift,
                'Assignment problem.',
                'Maximum amount of snoozes reached on assignment "'.$assignment->getTitle().'". Role: '.$assignment->getRole()->getRole()
            );

            $this->notificationService->notifyAboutProblemAssignment($assignment, $report);

            /** @var $history_role_repository HistoryEmployeeRoleRepository*/
            $history_role_repository = $this->entityManager->getRepository('ApiBundle:HistoryEmployeeRole');

            /** @var $history_role HistoryEmployeeRole */
            $history_role = $history_role_repository->getCurrentHistoryEmployeeRole($assignment->getRole());

            $this->createHistoryProblem($current_shift, !empty($history_role) ? $history_role->getEmployee() : null, $assignment);

            $assignment->setSnoozeUntil(null);

            $this->entityManager->persist($assignment);
            $this->entityManager->flush();

            return new JsonResponse(["error" => $this->translator->trans("assignments.reached_maximum_snoozes")]);
        }

        //Incrementing snooze count
        if($increaseSnoozeCount) {
            $assignment->setSnoozeCount($currentSnoozeCount + 1);
        }

        $snooze_until = new \DateTime();

        $snooze_until->setTimestamp($current_time->getTimestamp() + $assignment->getSnoozeTime());

        //Setting snooze_until (time when assignment will popup again)
        $assignment->setSnoozeUntil($snooze_until);

        $this->entityManager->persist($assignment);
        $this->entityManager->flush($assignment);

        return $assignment;
    }

    /**
     * Create record in table history_problem_task
     */
    public function createHistoryProblem($shift, $employee, $task)
    {
        $history_problem_task = new HistoryProblemTask();
        $history_problem_task->setBranchShift($shift);
        $history_problem_task->setEmployee($employee);
        $history_problem_task->setAssignment($task);
        $history_problem_task->setCreatedAt(new \DateTime());
        $this->entityManager->persist($history_problem_task);
        $this->entityManager->flush();
    }

}