<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 16.10.2017
 * Time: 17:57
 */

namespace WebSocketsBundle\Service;


use ApiBundle\Entity\Report\AbstractReport;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Admin;
use ApiBundle\Repository\User\AbstractUserRepository;
use ApiBundle\Repository\User\AdminRepository;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use ApiBundle\Entity\Report\ProblemReport;
use Doctrine\ORM\EntityManager;
use Gos\Bundle\WebSocketBundle\Pusher\PusherInterface;
use JMS\Serializer\Serializer;
use WebSocketsBundle\Entity\Notification\AlertNotification;
use WebSocketsBundle\Entity\Notification\ReportNotification;
use WebSocketsBundle\Entity\Notification\AnnouncementNotification;
use WebSocketsBundle\Repository\Notification\AlertNotificationRepository;
use WebSocketsBundle\Repository\Notification\AnnouncementNotificationRepository;

class NotificationService
{
    /** @var  EntityManager */
    protected $entityManager;

    /** @var  PusherInterface */
    protected $pusher;

    /** @var  Serializer */
    protected $serializer;

    public function __construct(EntityManager $entityManager, PusherInterface $pusher, Serializer $serializer)
    {
        $this->entityManager = $entityManager;
        $this->pusher = $pusher;
        $this->serializer = $serializer;
    }

    public function notifyAboutReport(AbstractReport $report)
    {
        $em = $this->entityManager;

        $branch = $report->getBranchShift()->getBranch();

        $push = [];

        //Notifying admins

        /** @var $adminRepository AdminRepository */
        $adminRepository = $this->entityManager->getRepository('ApiBundle:User\Admin');

        $admins = $adminRepository->getAdminsByRepository($branch->getCompany());

        foreach ($admins as $admin) {
            $notificationAdmin = new ReportNotification();
            $notificationAdmin->setReport($report);
            $notificationAdmin->setUser($admin);
            $em->persist($notificationAdmin);
            $push[] = $notificationAdmin;
        }

        //Notifying branch managers
        if($branch->getBranchManager() !== null)
        {
            $notificationBranchManager = new ReportNotification();
            $notificationBranchManager->setReport($report);
            $notificationBranchManager->setUser($branch->getBranchManager());
            $em->persist($notificationBranchManager);
            $push[] = $notificationBranchManager;
        }

        //Notifying co managers
        foreach($branch->getCoManagers() as $coManager)
        {
            $notificationCoManager = new ReportNotification();
            $notificationCoManager->setReport($report);
            $notificationCoManager->setUser($coManager);
            $em->persist($notificationCoManager);
            $push[] = $notificationCoManager;
        }

        //Notifying supervisor
        $supervisor = $branch->getSupervisor();
        $notificationSupervisor = new ReportNotification();
        $notificationSupervisor->setReport($report);
        $notificationSupervisor->setUser($supervisor);
        $em->persist($notificationSupervisor);
        $push[] = $notificationSupervisor;


        $em->flush();
        foreach($push as $notification)
        {
            $this->pusher->push(
                ['user' => $notification->getUser()->getId(), 'notification' => $this->serializer->serialize($notification, 'json')],
                'notification_topic',
                ['User' => $notification->getUser()]
            );
        }
    }

    public function notifyAboutProblemAssignment(AbstractAssignment $assignment, ProblemReport $problemReport)
    {
        switch ($assignment->getPriority()) {
            case AbstractAssignment::PRIORITY_NOTIFY_MANAGER:
                $user = $problemReport->getBranchStation()->getBranch()->getBranchManager();
                $notification = new ReportNotification();
                $notification->setReport($problemReport);
                $notification->setUser($user);
                $this->entityManager->persist($notification);
                $this->entityManager->flush();
                $this->pusher->push(
                    ['user' => $notification->getUser()->getId(), 'notification' => $this->serializer->serialize($notification, 'json')],
                    'notification_topic'
                );
                break;
            case AbstractAssignment::PRIORITY_NOTIFY_SUPERVISOR:
                $supervisor = $problemReport->getBranchStation()->getBranch()->getSupervisor();

                $user = $supervisor;
                $notification = new ReportNotification();
                $notification->setReport($problemReport);
                $notification->setUser($user);
                $this->entityManager->persist($notification);
                $this->entityManager->flush();
                $this->pusher->push(
                    ['user' => $notification->getUser()->getId(), 'notification' => $this->serializer->serialize($notification, 'json')],
                    'notification_topic'
                );

                break;
            case AbstractAssignment::PRIORITY_NOTIFY_COMPANY_OWNER:
                $push = [];
                /** @var $adminRepository AdminRepository */
                $adminRepository = $this->entityManager->getRepository('ApiBundle:User\Admin');
                $admins = $adminRepository->getAdminsByRepository($problemReport->getBranchStation()->getBranch()->getCompany());
                foreach ($admins as $admin) {
                    $notificationAdmin = new ReportNotification();
                    $notificationAdmin->setReport($problemReport);
                    $notificationAdmin->setUser($admin);
                    $this->entityManager->persist($notificationAdmin);
                    $push[] = $notificationAdmin;
                }
                $this->entityManager->flush();
                foreach($push as $notification) {
                    $this->pusher->push(
                        ['user' => $notification->getUser()->getId(), 'notification' => $this->serializer->serialize($notification, 'json')],
                        'notification_topic'
                    );
                }
                break;
            default:
                $user = null;
                break;
        }
    }

    /**
     * @param $notification_id integer
     * @param $users array
     * @return mixed
     */
    public function choose_users($notification_id, $users)
    {
        /** @var $notification_repository AnnouncementNotificationRepository*/
        $notification_repository = $this->entityManager->getRepository('WebSocketsBundle:Notification\AnnouncementNotification');

        /** @var $notification AnnouncementNotification*/
        $notification = $notification_repository->findOneBy(array('id' => $notification_id));

        if(empty($notification)) {
            return "Record not found";
        }

        /** @var AbstractUserRepository*/
        $repository_user = $this->entityManager->getRepository('ApiBundle:User\AbstractUser');

        foreach ($users as $user) {
            /** @var $current_user AbstractUser*/
            $current_user = $repository_user->findOneBy(array('id' => $user));
            if(!empty($current_user)) {
                $notification->addUser($current_user);
            }
            $this->entityManager->persist($notification);
            $this->entityManager->flush();
        }

        return $notification;
    }

    /**
     * @param $current_ip
     * @param AbstractUser $user
     * @return mixed
     */
    public function check_ip($user, $current_ip)
    {
        /** @var $alert_repository AlertNotificationRepository */
        $alert_repository = $this->entityManager->getRepository('WebSocketsBundle:Notification\AlertNotification');

        $ips = $alert_repository->findBy(array('user' => $user->getId()));

        foreach($ips as $ip) {
            /** @var $ip AlertNotification */
            if($ip->getIp() === $current_ip && !$ip->getConfirmed()) {
                return false;
            } elseif($ip->getIp() === $current_ip) {
                return true;
            }
        }

        $alert = new AlertNotification();
        $alert->setUser($user);
        $alert->setIp($current_ip);
        $this->entityManager->persist($alert);
        $this->entityManager->flush();

        return false;

    }
}