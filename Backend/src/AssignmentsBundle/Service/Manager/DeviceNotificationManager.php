<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 03.10.2017
 * Time: 19:14
 */

namespace AssignmentsBundle\Service\Manager;


use ApiBundle\Entity\BranchStation;
use ApiBundle\Entity\HistoryEmployeeRole;
use ApiBundle\Repository\HistoryEmployeeRoleRepository;
use AssignmentsBundle\Entity\Notification\DeviceNotification;
use AssignmentsBundle\Entity\Notification\DeviceNotificationBranch;
use AssignmentsBundle\Entity\Notification\DeviceNotificationMessage;
use AssignmentsBundle\Entity\Notification\DeviceNotificationRole;
use AssignmentsBundle\Entity\Notification\DeviceNotificationStation;
use AssignmentsBundle\Repository\Notification\DeviceNotificationRepository;
use AssignmentsBundle\Repository\Notification\Repeat\NotificationRepeatHistoryRepository;
use AssignmentsBundle\Service\Manager\Interfaces\DeviceNotificationManagerInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use JMS\Serializer\Serializer;
use RedjanYm\FCMBundle\FCMClient;
use AssignmentsBundle\Entity\Notification\Repeat\NotificationRepeatHistory;
use AssignmentsBundle\Entity\Notification\Repeat\RepeatMonth;
use AssignmentsBundle\Entity\Notification\Repeat\RepeatMonthDay;
use AssignmentsBundle\Entity\Notification\Repeat\RepeatWeekDay;
use RMS\PushNotificationsBundle\Message\iOSMessage;
use RMS\PushNotificationsBundle\Service\Notifications;

class DeviceNotificationManager implements DeviceNotificationManagerInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var  DeviceNotificationRepository */
    protected $repository;

    /** @var HistoryEmployeeRoleRepository */
    protected $history_role_repository;

    /** @var FCMClient */
    protected $fcm;

    /** @var Serializer */
    protected $serializer;

    /** @var Notifications */
    protected $rms;

    /** @var string */
    protected $format = 'Y-m-d H:i:s';

    public function __construct(EntityManager $entityManager, FCMClient $fcm, Serializer $serializer, Notifications $rms)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository('AssignmentsBundle:Notification\DeviceNotification');
        $this->history_role_repository = $this->entityManager->getRepository('ApiBundle:HistoryEmployeeRole');
        $this->fcm = $fcm;
        $this->serializer = $serializer;
        $this->rms = $rms;
    }

    public function handleDeviceNotifications()
    {
        $notifications = $this->repository->getNewNotifications();

        $count = count($notifications);

        $now = new \DateTime();

        foreach($notifications as $task)
        {
            /** @var $task DeviceNotification */

            /** @var $devices Collection Device */
            $devices = [];

            $data = $this->serializer->toArray($task);

            if(isset($data["role"])) {
                unset($data["role"]["assignments"]);
            }

            if($task instanceof DeviceNotificationMessage) {
                $devices = $this->history_role_repository->getOneRecord(array('employee' => $task->getEmployee(), 'end_time_null' => true))
                    ->getRole()->getBranchStation()->getDevices();
            }

            if($task instanceof DeviceNotificationRole) {
                $devices = $task->getRole()->getBranchStation()->getDevices();
            }

            if($task instanceof DeviceNotificationStation) {
                $devices = $task->getStation()->getDevices();
            }

            if($task instanceof DeviceNotificationBranch) {
                /** @var $stations Collection BranchStation */
                $stations = $task->getBranch()->getStations();
                foreach ($stations as $station) {
                    /** @var $station BranchStation */
                    $devices = array_merge($this->serializer->toArray($devices), $this->serializer->toArray($station->getDevices()));
                }
            }

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


            $task->setLastSentDate($now);

            //If task is repeatable
            if($task->getRepeatUnit() !== null)
            {
                $unit = $task->getRepeatUnit();
                $subunit = $task->getRepeatSubunit();

                switch ($task->getRepeatUnit())
                {
                    //1 = daily, 2 = weekly, 3 = monthly, 4 = every year
                    case 1:
                        if($subunit > 30) {
                            $subunit = 30;
                        }
                        break;
                    case 2:
                        if($subunit > 4) {
                            $subunit = 4;
                        }
                        break;
                    case 3:
                        if($subunit > 12) {
                            $subunit = 12;
                        }
                        break;
                    case 4:
                        break;
                    default:
                        $subunit = 1;
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

    public function createRepeatAssignment(DeviceNotification $notification, $unit, $subunit)
    {
        /** @var $repeat_history NotificationRepeatHistoryRepository */
        $repeat_history = $this->entityManager->getRepository('AssignmentsBundle\Entity\Notification\Repeat\NotificationRepeatHistory');
        $is_repeat = $repeat_history->findOneBy(array('parent' => 0, 'notification' => $notification->getId()));
        if(!$is_repeat) {
            $months_name = array(1 => 'Jan.', 2 => 'Feb.', 3 => 'Mar.', 4 => 'Apr.', 5 => 'May', 6 => 'Jun.', 7 => 'Jul.', 8 => 'Aug.', 9 => 'Sep.', 10 => 'Oct.', 11 => 'Nov.', 12 => 'Dec.');
            $week_days_name = array(1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
            $depth = $notification->getDepth();
            if($unit == DeviceNotification::REPEAT_DAILY)
            {
                $this->createRepeatHistory($notification);
                for($i = 1; $i < $depth; $i++) {
                    $offset = $subunit * $i;
                    $modifier = "+{$offset} day";
                    $this->cloneNotification($notification, $modifier);
                }
            }
            else if($unit == DeviceNotification::REPEAT_WEEKLY)
            {
                $this->createRepeatHistory($notification);
                /** @var $week_days Collection RepeatWeekDay*/
                $week_days = $notification->getRepeatWeekDays();
                for($i = 0; $i < $depth; $i++) {
                    foreach ($week_days as $day) {
                        /** @var $day RepeatWeekDay*/
                        if(intval($notification->getStartTime()->format('N')) < $day->getWeekDay() && $i == 0) {
                            $current_week_day = $day->getWeekDay() - intval($notification->getStartTime()->format('N'));
                            $modifier = "+{$current_week_day} day";
                        } elseif($i > 0) {
                            $offset = $subunit * $i * 7 + ($day->getWeekDay() - intval($notification->getStartTime()->format('N')));
                            $modifier = "+{$offset} day";
                        } else {
                            $modifier = null;
                        }
                        if(!empty($modifier)) {
                            $this->cloneNotification($notification, $modifier);
                        }
                    }
                }
            }
            else if($unit == DeviceNotification::REPEAT_MONTHLY)
            {
                $this->createRepeatHistory($notification);
                /** @var $month_days Collection RepeatMonthDay */
                $month_days = $notification->getRepeatMonthDays();
                $months = intval($notification->getStartTime()->format('m'));
                $year = intval(date('Y'));
                if(count($month_days)) {
                    for ($i = 0; $i < $depth; $i++) {
                        if($i > 0) {
                            $months += $subunit;
                            $year += intval($months / 12);
                        }
                        foreach ($month_days as $month_day) {
                            /** @var $month_day RepeatMonthDay */
                            if (intval($notification->getStartTime()->format('d')) < $month_day->getMonthDay() && $i == 0) {
                                $current_month_day = $month_day->getMonthDay() - intval($notification->getStartTime()->format('d'));
                                $modifier = "+{$current_month_day} day";
                            } elseif($i > 0) {
                                $offset = (int) $notification->getStartTime()->diff((new \DateTime())->setDate($year, $months%12 == 0 ? 12 : $months%12, $month_day->getMonthDay()))->format('%a');
                                $modifier = "+{$offset} day";
                            } else {
                                $modifier = null;
                            }
                            if(!empty($modifier)) {
                                if (intval((new \DateTime($notification->getStartTime()->format($this->format)))->modify($modifier)->format('d')) !== $month_day->getMonthDay()) {
                                    continue;
                                }
                                $this->cloneNotification($notification, $modifier);
                            }
                        }
                    }
                } else {
                    $week = $notification->getRepeatWeek();
                    /** @var $week_days Collection RepeatWeekDay*/
                    $week_days = $notification->getRepeatWeekDays();
                    for ($i = 0; $i < $depth; $i++) {
                        if ($i > 0) {
                            $months += $subunit;
                            $year += intval($months / 12);
                        }
                        foreach ($week_days as $week_day) {
                            $m = $months % 12 == 0 ? 12 : $months % 12;
                            /** @var $week_day RepeatWeekDay*/
                            $date = $this->required_date($months_name[$m], $week, $week_days_name[$week_day->getWeekDay()], $year);
                            if(intval($notification->getStartTime()->format('d')) < intval($date->format('d')) && $i == 0 || $i > 0) {
                                $offset = (int) $notification->getStartTime()->diff($date)->format('%a')+1;
                                $modifier = "+{$offset} day";
                            } else {
                                $modifier = null;
                            }
                            if(!empty($modifier)) {
                                if (intval((new \DateTime($notification->getStartTime()->format($this->format)))->modify($modifier)->format('m')) !== $m) {
                                    continue;
                                }
                                $this->cloneNotification($notification, $modifier);
                            }
                        }
                    }
                }
            }
            else if($unit == DeviceNotification::REPEAT_YEARLY)
            {
                $this->createRepeatHistory($notification);
                /** @var $months Collection RepeatMonth*/
                $months = $notification->getRepeatMonths();
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
                    $week_days = $notification->getRepeatWeekDays();
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
                                    if ($notification->getStartTime()->getTimestamp() < $date->getTimestamp() && $i == 0 || $i > 0) {
                                        $offset = (int)$notification->getStartTime()->diff($date)->format('%a');
                                        $modifier = "+{$offset} day";
                                    } else {
                                        $modifier = null;
                                    }
                                    if(intval((new \DateTime($notification->getStartTime()->format($this->format)))->modify($modifier)->format('d')) !== $month_day) {
                                        continue;
                                    }
                                    $this->cloneNotification($notification, $modifier);
                                }
                            }
                        }
                    } else {
                        $week = $notification->getRepeatWeek();
                        for ($i = 0; $i < $depth; $i++) {
                            if ($i > 0) {
                                $year += $subunit;
                            }
                            foreach ($months as $month) {
                                /** @var $month RepeatMonth */
                                foreach ($week_days as $week_day) {
                                    /** @var $week_day RepeatWeekDay */
                                    $date = $this->required_date($months_name[$month->getMonth()], $week, $week_days_name[$week_day->getWeekDay()], $year);
                                    if ($notification->getStartTime()->getTimestamp() < $date->getTimestamp() && $i == 0 || $i > 0) {
                                        $offset = (int)$notification->getStartTime()->diff($date)->format('%a') + 1;
                                        $modifier = "+{$offset} day";
                                    } else {
                                        $modifier = null;
                                    }
                                    if (!empty($modifier)) {
                                        $this->cloneNotification($notification, $modifier);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    protected function createRepeatHistory(DeviceNotification $notification, $parent = 0)
    {
        $repeat_history = new NotificationRepeatHistory();
        $repeat_history->setNotification($notification);
        $repeat_history->setParent($parent);
        $repeat_history->setDateAdded(new \DateTime());
        $this->entityManager->persist($repeat_history);
        $this->entityManager->flush();
    }

    public function cloneNotification(DeviceNotification $notification, $modifier)
    {
        $new_notification = null;
        switch (true) {
            case $notification instanceof DeviceNotificationBranch:
                $new_notification = new DeviceNotificationBranch();
                $this->cloneBaseProperties($new_notification, $notification);
                $new_notification->setBranch($notification->getBranch());
                $new_notification->setStartTime((new \DateTime($notification->getStartTime()->format($this->format)))->modify($modifier));
                $new_notification->setEndTime((new \DateTime($notification->getEndTime()->format($this->format)))->modify($modifier));
                $this->entityManager->persist($new_notification);
                $this->entityManager->flush();
                break;
            case $notification instanceof DeviceNotificationMessage:
                $new_notification = new DeviceNotificationMessage();
                $this->cloneBaseProperties($new_notification, $notification);
                $new_notification->setEmployee($notification->getEmployee());
                $new_notification->setStartTime((new \DateTime($notification->getStartTime()->format($this->format)))->modify($modifier));
                $new_notification->setEndTime((new \DateTime($notification->getEndTime()->format($this->format)))->modify($modifier));
                $this->entityManager->persist($new_notification);
                $this->entityManager->flush();
                break;
            case $notification instanceof DeviceNotificationRole:
                $new_notification = new DeviceNotificationRole();
                $this->cloneBaseProperties($new_notification, $notification);
                $new_notification->setRole($notification->getRole());
                $new_notification->setStartTime((new \DateTime($notification->getStartTime()->format($this->format)))->modify($modifier));
                $new_notification->setEndTime((new \DateTime($notification->getEndTime()->format($this->format)))->modify($modifier));
                $this->entityManager->persist($new_notification);
                $this->entityManager->flush();
                break;
            case $notification instanceof DeviceNotificationStation:
                $new_notification = new DeviceNotificationStation();
                $this->cloneBaseProperties($new_notification, $notification);
                $new_notification->setStation($notification->getStation());
                $new_notification->setStartTime((new \DateTime($notification->getStartTime()->format($this->format)))->modify($modifier));
                $new_notification->setEndTime((new \DateTime($notification->getEndTime()->format($this->format)))->modify($modifier));
                $this->entityManager->persist($new_notification);
                $this->entityManager->flush();
                break;
            default:
                break;
        }
        if(!empty($new_notification)) {
            $this->createRepeatHistory($new_notification, $notification->getId());
        }
    }

    protected function cloneBaseProperties(DeviceNotification &$new_notification, DeviceNotification $notification)
    {
        $new_notification->setDescription($notification->getDescription());
        $new_notification->setViewTime($notification->getViewTime());
        $new_notification->setTitle($notification->getTitle());
        return $new_notification;
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
}