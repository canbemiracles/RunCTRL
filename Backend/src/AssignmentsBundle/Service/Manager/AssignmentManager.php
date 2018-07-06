<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 22.09.2017
 * Time: 14:35
 */

namespace AssignmentsBundle\Service\Manager;


use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\Role\AbstractBranchStationRole;
use ApiBundle\Entity\ShiftDay;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Repository\BranchShiftRepository;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use AssignmentsBundle\Entity\Assignment\Checklist\Checklist;
use AssignmentsBundle\Entity\Assignment\Checklist\Tasks;
use AssignmentsBundle\Entity\Assignment\HistoryProblemTask;
use AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionAnswerList;
use AssignmentsBundle\Entity\Assignment\Question\AnswerList\QuestionPossibleAnswer;
use AssignmentsBundle\Entity\Assignment\Question\QuestionNumeric;
use AssignmentsBundle\Entity\Assignment\Question\QuestionRange;
use AssignmentsBundle\Entity\Assignment\Question\QuestionText;
use AssignmentsBundle\Entity\Assignment\Question\QuestionYesNo;
use AssignmentsBundle\Entity\Assignment\Repeat\AssignmentRepeatHistory;
use AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonth;
use AssignmentsBundle\Entity\Assignment\Repeat\RepeatMonthDay;
use AssignmentsBundle\Entity\Assignment\Repeat\RepeatWeekDay;
use AssignmentsBundle\Entity\Assignment\StandardTask;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use AssignmentsBundle\Repository\Assignment\HistoryProblemTaskRepository;
use AssignmentsBundle\Repository\Assignment\Repeat\AssignmentRepeatHistoryRepository;
use AssignmentsBundle\Repository\Assignment\StandardTaskRepository;
use AssignmentsBundle\Service\AssignmentHandler;
use AssignmentsBundle\Service\BaseAssignmentHandler;
use AssignmentsBundle\Service\Manager\Interfaces\AssignmentManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use ApiBundle\Entity\HistoryEmployeeRole;
use RedjanYm\FCMBundle\FCMClient;
use JMS\Serializer\Serializer;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use RMS\PushNotificationsBundle\Service\Notifications;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;

class AssignmentManager implements AssignmentManagerInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var AbstractAssignmentRepository*/
    protected $repository;

    /** @var FCMClient */
    protected $fcm;

    /** @var Serializer */
    protected $serializer;

    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var  AssignmentHandler */
    protected $assignmentHandler;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var Notifications */
    protected $rms;

    public function __construct(EntityManager $entityManager, FCMClient $fcm, Serializer $serializer, TokenStorage $tokenStorage, AssignmentHandler $assignmentHandler,
                                TranslatorInterface $translator, Notifications $rms)
    {
        $this->entityManager = $entityManager;
        $this->fcm = $fcm;
        $this->serializer = $serializer;
        $this->repository = $this->entityManager->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');
        $this->tokenStorage = $tokenStorage;
        $this->assignmentHandler = $assignmentHandler;
        $this->translator = $translator;
        $this->rms = $rms;
    }

    public function handleNewAndRepeatableAssignments()
    {
        $assignments = $this->repository->getNewAndRepeatableAssignments();
        
        $count = count($assignments);

        $now = new \DateTime();

        foreach($assignments as $task)
        {
            if($this->assignmentHandler->isAlreadyAnswered($task)) {
                continue;
            }
            /** @var $task AbstractAssignment */

            /** @var $devices Collection Device */

            $role = $task->getRole();
            if($role != null) {
                $devices = $role->getBranchStation()->getDevices();
            }
            else {
                $devices = [];
            }

            $data = $this->serializer->toArray($task);
            //TODO: fix this shit code...
            unset($data["role"]["assignments"]);
            unset($data["answers"]);

            foreach ($devices as $device) {
                /** @var $device Device */
                $type = current(explode("_", $device->getUsername()));
                if (!empty($device->getToken())) {
                    if($type == "android") {
                        $notification = $this->fcm->createDeviceNotification(
                            'Task',
                            null,
                            $device->getToken()
                        );
                        $notification->setPriority('high');
                        $notification->setData($this->serializer->toArray($data));
                        $this->fcm->sendNotification($notification);
                    } elseif ($type == "ipad") {
                        $message = new iOSMessage();
                        $message->setMessage($this->serializer->toArray($data));
                        //TODO temporarily
                        $message->setDeviceIdentifier("f44b356a3bccecdabb43f3b01733c1348d5c6ace");
                        $this->rms->send($message);
                    }
                }
            }
            $task->setLastSentDate($now);

            //If task is repeatable
            if($task->getRepeatUnit() != null)
            {
                $unit = $task->getRepeatUnit();
                $subunit = $task->getRepeatSubunit();
                if($subunit < 1 || $subunit == null) {
                    $subunit = 1;
                }

                if($unit == AbstractAssignment::REPEAT_DAILY)
                {
                    if($subunit > 30) {
                        $subunit = 30;
                    }
                }
                else if($unit == AbstractAssignment::REPEAT_WEEKLY)
                {
                    if($subunit > 4) {
                        $subunit = 4;
                    }
                }
                else if($unit == AbstractAssignment::REPEAT_MONTHLY && $subunit > 12)
                {
                    $subunit = 12;
                }

                if(!empty($unit) && !empty($subunit)) {
                    $this->createRepeatAssignment($task, $unit, $subunit);
                }
            }
            $this->entityManager->persist($task);
        }
        $this->entityManager->flush();

        return $count;

    }

    public function handleSnoozedAssignments()
    {
        $assignments = $this->repository->getSnoozedAssignments();

        $count = count($assignments);

        foreach ($assignments as $assignment) {
            if($this->assignmentHandler->isAlreadyAnswered($assignment)) {
                continue;
            }
            /** @var $assignment AbstractAssignment */

            /** @var $devices Collection Device */

            $role = $assignment->getRole();
            if($role != null) {
                $devices = $role->getBranchStation()->getDevices();
            }
            else {
                $devices = [];
            }

            $data = $this->serializer->toArray($assignment);
            //TODO: fix this shit code...
            unset($data["role"]["assignments"]);
            unset($data["answers"]);

            foreach ($devices as $device) {
                $type = current(explode("_", $device->getUsername()));
                if (!empty($device->getToken())) {
                    if($type == "android") {
                        $notification = $this->fcm->createDeviceNotification(
                            'Task',
                            null,
                            $device->getToken()
                        );
                        $notification->setPriority('high');
                        $notification->setData($this->serializer->toArray($data));
                        $this->fcm->sendNotification($notification);
                    } elseif ($type == "ipad") {
                        $message = new iOSMessage();
                        $message->setMessage($this->serializer->toArray($data));
                        //TODO temporarily
                        $message->setDeviceIdentifier("f44b356a3bccecdabb43f3b01733c1348d5c6ace");
                        $this->rms->send($message);
                    }
                }
            }


        }

        return $count;
    }

    public function handleConfirmationAssignments()
    {
        /** @var $repository_standard_task StandardTaskRepository*/
        $repository_standard_task = $this->entityManager->getRepository('AssignmentsBundle:Assignment\StandardTask');
        $assignments = $repository_standard_task->getConfirmationAssignments();
        $count = count($assignments);
        $now = new \DateTime();
        foreach($assignments as $task) {
            /** @var $task StandardTask */
            if($this->assignmentHandler->isAlreadyAnswered($task)) {
                continue;
            }
            if($now->getTimestamp() < $task->getLastTimeSendConfirmation()->modify("+ {$task->getTimeConfirmation()} seconds")->getTimestamp()) {
                continue;
            }
            $role = $task->getRole();
            if($role != null) {
                $devices = $role->getBranchStation()->getDevices();
            }
            else {
                $devices = [];
            }

            $data = $this->serializer->toArray($task);

            foreach ($devices as $device) {
                if(!empty( $device->getToken())) {
                    $notification = $this->fcm->createDeviceNotification(
                        'Task',
                        $this->serializer->toArray($data),
                        $device->getToken()
                    );
                    $notification->setPriority('high');
                    $notification->setData($this->serializer->toArray($data));
                    $this->fcm->sendNotification($notification);
                }
            }

            $task->setLastTimeSendConfirmation($now);
            $task->setHasConfirmation(false);
            $this->entityManager->persist($task);
        }
        $this->entityManager->flush();
        return $count;
    }

    public function getListAssignmentsByRole(BranchShift $shift, AbstractBranchStationRole $role, $future = false)
    {
        /** @var $assignment_repository AbstractAssignmentRepository*/
        $assignment_repository = $this->entityManager->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        /** @var $history_problem_task_repository HistoryProblemTaskRepository*/
        $history_problem_task_repository = $this->entityManager->getRepository('AssignmentsBundle:Assignment\HistoryProblemTask');

        $fail_tasks = [];
        $history = $history_problem_task_repository->getProblemsTasksByRole($role, $shift);
        foreach($history as $problemTask) {
            /** @var $problemTask HistoryProblemTask */
            $fail_tasks[] = $assignment_repository->findOneBy(['id' => $problemTask->getAssignment()->getId()]);
        }

        if($future) {
             $response = [
                 'pending' => $assignment_repository->getPendingTasksByRole($role, $shift),
                 'done' => $assignment_repository->getDoneTasksByRole($role, $shift),
                 'fail' => $fail_tasks,
                 'future' => $assignment_repository->getFutureTasksByRole($role, $shift)
             ];
        } else {
            $response = [
                'pending' => $assignment_repository->getPendingTasksByRole($role, $shift),
                'done' => $assignment_repository->getDoneTasksByRole($role, $shift),
                'fail' => $fail_tasks
            ];
        }

        return $response;
    }

    public function getAllAssignmentsByRole(BranchShift $shift, AbstractBranchStationRole $role, $future = false)
    {
        /** @var $assignment_repository AbstractAssignmentRepository*/
        $assignment_repository = $this->entityManager->getRepository('AssignmentsBundle:Assignment\AbstractAssignment');

        return $assignment_repository->getAllTasksByRole($role, $shift, $future);
    }

    public function checkProblemTasks()
    {
        /** @var $tasks Collection AbstractAssignment */
        $tasks = $this->repository->getListProblemsTasks(array(
            'date_time_start' => date('Y-m-d'),
            'date_time_end' => date('Y-m-d H:i:s'),
        ));

        $day_of_week = date('N', strtotime(date('Y-m-d')));

        /** @var $shift_day ShiftDay */
        $shift_day = $this->entityManager->getRepository('ApiBundle:ShiftDay')->findOneBy(['day' => $day_of_week]);

        if($shift_day === null) {
            return new JsonResponse(["error" => $this->translator->trans("shift_management.shift_day_not_found")]);
        }

        $current_time = new \DateTime();

        /** @var $repository_shift BranchShiftRepository */
        $repository_shift = $this->entityManager->getRepository('ApiBundle:BranchShift');

        /** @var $repository_history_role HistoryEmployeeRoleRepository */
        $repository_history_role = $this->entityManager->getRepository('ApiBundle:HistoryEmployeeRole');

        /** @var $repository_history_problem HistoryProblemTaskRepository */
        $repository_history_problem = $this->entityManager->getRepository('AssignmentsBundle:Assignment\HistoryProblemTask');

        $message = "";

        foreach ($tasks as $task) {
            /** @var $task AbstractAssignment */

            $exist = $repository_history_problem->findOneBy(['assignment' => $task]);

            // if current task not exist in table history_problem
            if(empty($exist)) {
                /** @var $current_shift BranchShift */
                $shift = $repository_shift
                    ->getCurrentShift([
                        'branch' => $task->getRole()->getBranchStation()->getBranch()->getId(),
                        'filter_shift_day' => $shift_day->getId(),
                        'filter_time_open' => $task->getStartTime(),
                        'filter_time_close' => $task->getEndTime()]);

                /** @var $history_role HistoryEmployeeRole */
                $history_role = $repository_history_role->getCurrentHistoryEmployeeRole($task->getRole());
                $this->assignmentHandler->createHistoryProblem($shift, !empty($history_role) ? $history_role->getEmployee() : null, $task);
                $message .= "[".$current_time->format('d.m.Y H:i:s')."] Task № {$task->getId()} attached to problem_history \n\r";
            }
        }

        return $message;
    }

    public function createRepeatAssignment(AbstractAssignment $assignment, $unit, $subunit)
    {
        /** @var $repeat_history AssignmentRepeatHistoryRepository */
        $repeat_history = $this->entityManager->getRepository('AssignmentsBundle\Entity\Assignment\Repeat\AssignmentRepeatHistory');
        $is_repeat = $repeat_history->findOneBy(array('parent' => 0, 'assignment' => $assignment->getId()));

        if(!$is_repeat) {
            $months_name = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
            $week_days_name = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
            $depth = $assignment->getDepth();
            if($unit == AbstractAssignment::REPEAT_DAILY)
            {
               $this->repeatDaily($assignment, $depth, $subunit);
            }
            else if($unit == AbstractAssignment::REPEAT_WEEKLY)
            {
                $this->repeatWeekly($assignment, $depth, $subunit);
            }
            else if($unit == AbstractAssignment::REPEAT_MONTHLY)
            {
               $this->repeatMonthly($assignment, $depth, $subunit, $months_name, $week_days_name);
            }
            else if($unit == AbstractAssignment::REPEAT_YEARLY)
            {
                $this->repeatYearly($assignment, $depth, $subunit, $months_name, $week_days_name);
            }
        }
    }

    protected function repeatDaily($assignment, $depth, $subunit)
    {
        $this->createRepeatHistory($assignment);
        for($i = 1; $i < $depth; $i++) {
            $offset = $subunit * $i;
            $modifier = "+{$offset} day";
            $this->cloneAssignment($assignment, $modifier);
        }
    }

    /**
     * @param $assignment AbstractAssignment
    */
    protected function repeatWeekly($assignment, $depth, $subunit)
    {
        $this->createRepeatHistory($assignment);
        /** @var $week_days Collection RepeatWeekDay*/
        $week_days = $assignment->getRepeatWeekDays();
        for($i = 0; $i < $depth; $i++) {
            foreach ($week_days as $day) {
                /** @var $day RepeatWeekDay*/
                if(intval($assignment->getStartTime()->format('N')) < $day->getWeekDay() && $i == 0) {
                    $current_week_day = $day->getWeekDay() - intval($assignment->getStartTime()->format('N'));
                    $modifier = "+{$current_week_day} day";
                } elseif($i > 0) {
                    $offset = $subunit * $i * 7 + ($day->getWeekDay() - intval($assignment->getStartTime()->format('N')));
                    $modifier = "+{$offset} day";
                } else {
                    $modifier = null;
                }
                if(!empty($modifier)) {
                    $this->cloneAssignment($assignment, $modifier);
                }
            }
        }
    }

    /**
     * @param $assignment AbstractAssignment
     */
    protected function repeatMonthly($assignment, $depth, $subunit, $months_name, $week_days_name)
    {
        $this->createRepeatHistory($assignment);
        /** @var $month_days Collection RepeatMonthDay */
        $month_days = $assignment->getRepeatMonthDays();
        $months = intval($assignment->getStartTime()->format('m'));
        $year = intval(date('Y'));
        if(count($month_days)) {
            for ($i = 0; $i < $depth; $i++) {
                if($i > 0) {
                    $months += $subunit;
                    $year += intval($months / 12);
                }
                foreach ($month_days as $month_day) {
                    /** @var $month_day RepeatMonthDay */
                    if (intval($assignment->getStartTime()->format('d')) < $month_day->getMonthDay() && $i == 0) {
                        $current_month_day = $month_day->getMonthDay() - intval($assignment->getStartTime()->format('d'));
                        $modifier = "+{$current_month_day} day";
                    } elseif($i > 0) {
                        $offset = (int) $assignment->getStartTime()->diff((new \DateTime())->setDate($year, $months%12 == 0 ? 12 : $months%12, $month_day->getMonthDay()))->format('%a');
                        $modifier = "+{$offset} day";
                    } else {
                        $modifier = null;
                    }
                    if(!empty($modifier)) {
                        if (intval($assignment->getStartTime()->modify($modifier)->format('d')) !== $month_day->getMonthDay()) {
                            continue;
                        }
                        $this->cloneAssignment($assignment, $modifier);
                    }
                }
            }
        } else {
            $week = $assignment->getRepeatWeek();
            /** @var $week_days Collection RepeatWeekDay*/
            $week_days = $assignment->getRepeatWeekDays();
            for ($i = 0; $i < $depth; $i++) {
                if ($i > 0) {
                    $months += $subunit;
                    $year += intval($months / 12);
                }
                foreach ($week_days as $week_day) {
                    /** @var $week_day RepeatWeekDay*/
                    $date = $this->required_date($months_name[$months % 12 == 0 ? 12 : $months % 12], $week, $week_days_name[$week_day->getWeekDay()], $year);
                    if(intval($assignment->getStartTime()->format('d')) < intval($date->format('d')) && $i == 0 || $i > 0) {
                        $offset = (int) $assignment->getStartTime()->diff($date)->format('%a')+1;
                        $modifier = "+{$offset} day";
                    } else {
                        $modifier = null;
                    }
                    if(!empty($modifier)) {
                        $m = $months % 12 == 0 ? 12 : $months % 12;
                        if (intval($assignment->getStartTime()->modify($modifier)->format('m')) !== $m) {
                            continue;
                        }
                        $this->cloneAssignment($assignment, $modifier);
                    }
                }
            }
        }
    }

    /**
     * @param $assignment AbstractAssignment
     */
    protected function repeatYearly($assignment, $depth, $subunit, $months_name, $week_days_name)
    {
        $this->createRepeatHistory($assignment);
        /** @var $months Collection RepeatMonth*/
        $months = $assignment->getRepeatMonths();
        $year = intval(date('Y'));
        if(!empty($months)) {
            $check_has_month_days = true;
            foreach ($months as $month) {
                /** @var $month RepeatMonth*/
                if(count($month->getMonthDays()) == 0) {
                    $check_has_month_days = false;
                }
            }
            /** @var $week_days Collection RepeatWeekDay */
            $week_days = $assignment->getRepeatWeekDays();
            if ($check_has_month_days) {
                for ($i = 0; $i < $depth; $i++) {
                    if ($i > 0) {
                        $year += $subunit;
                    }
                    foreach ($months as $month) {
                        /** @var $month RepeatMonth */
                        foreach ($month->getMonthDays() as $month_day) {
                            $month_day = intval($month_day);
                            $date = (new \DateTime())->setDate($year, $month->getMonth(), $month_day);
                            if ($assignment->getStartTime()->getTimestamp() < $date->getTimestamp() && $i == 0 || $i > 0) {
                                $offset = (int)$assignment->getStartTime()->diff($date)->format('%a');
                                $modifier = "+{$offset} day";
                            } else {
                                $modifier = null;
                            }
                            if(intval($assignment->getStartTime()->modify($modifier)->format('d')) !== $month_day) {
                                continue;
                            }
                            $this->cloneAssignment($assignment, $modifier);
                        }
                    }
                }
            } else {
                $week = $assignment->getRepeatWeek();
                for ($i = 0; $i < $depth; $i++) {
                    if ($i > 0) {
                        $year += $subunit;
                    }
                    foreach ($months as $month) {
                        /** @var $month RepeatMonth */
                        foreach ($week_days as $week_day) {
                            /** @var $week_day RepeatWeekDay */
                            $date = $this->required_date($months_name[$month->getMonth()], $week, $week_days_name[$week_day->getWeekDay()], $year);
                            if ($assignment->getStartTime()->getTimestamp() < $date->getTimestamp() && $i == 0 || $i > 0) {
                                $offset = (int)$assignment->getStartTime()->diff($date)->format('%a') + 1;
                                $modifier = "+{$offset} day";
                            } else {
                                $modifier = null;
                            }
                            if (!empty($modifier)) {
                                $this->cloneAssignment($assignment, $modifier);
                            }
                        }
                    }
                }
            }
        }
    }

    protected function createRepeatHistory(AbstractAssignment $assignment, $parent = 0)
    {
        $repeat_history = new AssignmentRepeatHistory();
        $repeat_history->setAssignment($assignment);
        $repeat_history->setParent($parent);
        $repeat_history->setDateAdded(new \DateTime());
        $this->entityManager->persist($repeat_history);
        $this->entityManager->flush();
    }

    /**
     * Returns the date of the given day in a given month.
     *
     * @param String $month The month in question eg January, September
     * @param integer $week_num The number of the week, remembering that some months will have a 5th week
     * @param String $day The day to find eg 'Monday'
     *
     * @return \DateTime
     */
    protected  function required_date($month, $week_num, $day, $year)
    {
        /**
         * @var \DateTime[] $weeks
         */
        $weeks = array();
        $firstDayOfMonth = new \DateTime("1st $month $year");
        $lastDayOfMonth = new \DateTime($firstDayOfMonth->format("t M Y"));
        $oneDay = new \DateInterval('P1D');
        $period = new \DatePeriod($firstDayOfMonth, $oneDay, $lastDayOfMonth->add($oneDay));

        foreach($period as $date)
        {
            /** @var \DateTime $date */
            $weekNumber = ceil((int)$date->format('d')/7);
            $weeks[$weekNumber][$date->format('l')] = $date;
        }
        return $weeks[$week_num][$day];
    }
    
    public function cloneAssignment(AbstractAssignment $assignment, $modifier)
    {
        switch (true) {
            case $assignment instanceof StandardTask:
                $new_assignment = new StandardTask();
                $this->cloneBaseProperties($new_assignment, $assignment);
                $new_assignment->setDescription($assignment->getDescription());
                $new_assignment->setStartTime($assignment->getStartTime()->modify($modifier));
                $new_assignment->setEndTime($assignment->getEndTime()->modify($modifier));
                $this->entityManager->persist($new_assignment);
                $this->entityManager->flush();
                break;
            case $assignment instanceof Checklist:
                $new_assignment = new Checklist();
                $this->cloneBaseProperties($new_assignment, $assignment);
                foreach($assignment->getTasks() as $task) {
                    /** @var $task Tasks*/
                    $new_task = new Tasks();
                    $new_task->setTask($task->getTask());
                    $new_task->setChecklist($new_assignment);
                    $this->entityManager->persist($new_task);
                    $new_assignment->addTask($new_task);
                }
                $new_assignment->setStartTime($assignment->getStartTime()->modify($modifier));
                $new_assignment->setEndTime($assignment->getEndTime()->modify($modifier));
                $this->entityManager->persist($new_assignment);
                $this->entityManager->flush();
                break;
            case $assignment instanceof QuestionAnswerList:
                $new_assignment = new QuestionAnswerList();
                $this->cloneBaseProperties($new_assignment, $assignment);
                foreach($assignment->getPossibleAnswers() as $answer) {
                    /** @var $answer QuestionPossibleAnswer*/
                    $new_answer = new QuestionPossibleAnswer();
                    $new_answer->setAnswer($answer->getAnswer());
                    $new_answer->setQuestion($new_assignment);
                    $this->entityManager->persist($new_answer);
                    $new_assignment->addPossibleAnswer($new_answer);
                }
                $new_assignment->setStartTime($assignment->getStartTime()->modify($modifier));
                $new_assignment->setEndTime($assignment->getEndTime()->modify($modifier));
                $this->entityManager->persist($new_assignment);
                $this->entityManager->flush();
                break;
            case $assignment instanceof QuestionNumeric:
                $new_assignment = new QuestionNumeric();
                $this->cloneBaseProperties($new_assignment, $assignment);
                $new_assignment->setStartTime($assignment->getStartTime()->modify($modifier));
                $new_assignment->setEndTime($assignment->getEndTime()->modify($modifier));
                $this->entityManager->persist($new_assignment);
                $this->entityManager->flush();
                break;
            case $assignment instanceof QuestionRange:
                $new_assignment = new QuestionRange();
                $this->cloneBaseProperties($new_assignment, $assignment);
                $new_assignment->setStartTime($assignment->getStartTime()->modify($modifier));
                $new_assignment->setEndTime($assignment->getEndTime()->modify($modifier));
                $this->entityManager->persist($new_assignment);
                $this->entityManager->flush();
                break;
            case $assignment instanceof QuestionText:
                $new_assignment = new QuestionText();
                $this->cloneBaseProperties($new_assignment, $assignment);
                $new_assignment->setStartTime($assignment->getStartTime()->modify($modifier));
                $new_assignment->setEndTime($assignment->getEndTime()->modify($modifier));
                $this->entityManager->persist($new_assignment);
                $this->entityManager->flush();
                break;
            case $assignment instanceof QuestionYesNo:
                $new_assignment = new QuestionYesNo();
                $this->cloneBaseProperties($new_assignment, $assignment);
                $new_assignment->setStartTime($assignment->getStartTime()->modify($modifier));
                $new_assignment->setEndTime($assignment->getEndTime()->modify($modifier));
                $this->entityManager->persist($new_assignment);
                $this->entityManager->flush();
                break;
            default:
                $new_assignment = null;
                break;
        }
        if(!empty($new_assignment)) {
            $this->createRepeatHistory($new_assignment, $assignment->getId());
        }
    }

    protected function cloneBaseProperties(AbstractAssignment &$new_assignment, AbstractAssignment $assignment)
    {
        $new_assignment->setRole($assignment->getRole());
        $new_assignment->setStation($assignment->getStation());
        $new_assignment->setPriority($assignment->getPriority());
        $new_assignment->setImportance($assignment->getImportance());
        $new_assignment->setSnoozeCount($assignment->getSnoozeCount());
        $new_assignment->setSnoozeTime($assignment->getSnoozeTime());
        $new_assignment->setSnoozeMax($assignment->getSnoozeMax());
        $new_assignment->setTitle($assignment->getTitle());
        $new_assignment->setViewTime($assignment->getViewTime());
        return $new_assignment;
    }
}