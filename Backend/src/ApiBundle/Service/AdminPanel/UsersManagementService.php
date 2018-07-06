<?php

namespace ApiBundle\Service\AdminPanel;

use ApiBundle\Entity\Company;
use ApiBundle\Entity\Notification\Alert;
use ApiBundle\Entity\Notification\CustomNotification;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Admin;
use ApiBundle\Repository\BranchRepository;
use ApiBundle\Repository\Card\PaymentRepository;
use ApiBundle\Repository\Notification\AlertRepository;
use ApiBundle\Repository\Notification\CustomNotificationRepository;
use ApiBundle\Repository\Security\RecentLoginRepository;
use ApiBundle\Repository\User\AbstractUserRepository;
use ApiBundle\Repository\User\AdminRepository;
use ApiBundle\Repository\User\Device\DeviceRepository;
use ApiBundle\Service\Branch\BranchService;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Mailer\TwigSwiftMailer;
use Symfony\Component\Translation\TranslatorInterface;

class UsersManagementService
{
    /** @var EntityManager */
    protected $em;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var TwigSwiftMailer */
    protected $mailer;

    /** @var BranchService*/
    protected $branch_service;

    public function __construct(EntityManager $em, TranslatorInterface $translator, TwigSwiftMailer $mailer, BranchService $branch_service) {
        $this->em = $em;
        $this->translator = $translator;
        $this->mailer = $mailer;
        $this->branch_service = $branch_service;
    }

    /**
     * @return mixed
    */
    function getInfo() {
        /** @var AbstractUserRepository*/
        $repository_user = $this->em->getRepository('ApiBundle:User\AbstractUser');

        /** @var Collection AbstractUser*/
        $users = $repository_user->findAll();

        $data = [];

        /** @var PaymentRepository */
        $repository_payment = $this->em->getRepository('ApiBundle:Card\Payment');

        /** @var RecentLoginRepository */
        $recent_login_payment = $this->em->getRepository('ApiBundle:Security\RecentLogin');

        foreach ($users as $user) {
            /** @var $user AbstractUser */
            $data[] = array(
                'user_id' => $user->getId(),
                'last_login' => $user->getLastLogin(),
                'country' => $user->getCompany()->getGeographicalArea()->getCountry()->getName(),
                'first_name' => $user->getFirstName(),
                'last_name' => $user->getLastName(),
                'email' => $user->getEmail(),
                'company_name' => $user->getCompany()->getName(),
                'phone' => $user->getPhoneNumber(),
                'status' => $user instanceof Admin
                    ? array('name' => 'company_owner', 'plan' => $user->getCompany()->getPlan(), 'plan_payed_until' => $user->getCompany()->getPlanPayedUntil())
                    : array('name' => 'employee'),
                'invoice_log' => $repository_payment->findBy(array('user' => $user->getId())),
                'user_log' => $recent_login_payment->findBy(array('user' => $user->getId()))
            );
        }
        return $data;
    }

    /**
     * Activate / Deactivate user. If param "block" is 1 to activate, if it is 0 to deactivate
     * @param integer $user_id
     * @param integer $block
     * @return mixed
     */
    function userBlock($user_id, $block)
    {
        /** @var AbstractUserRepository*/
        $repository_user = $this->em->getRepository('ApiBundle:User\AbstractUser');

        /** @var $user AbstractUser*/
        $user = $repository_user->findOneBy(array('id' => $user_id));

        if(empty($user)) {
            return $this->translator->trans("user_not_found");
        }

        if(intval($block) === 1) {
            $user->setEnabled(true);
        } else if(intval($block) === 0) {
            $user->setEnabled(false);
            if($user instanceof Admin) {
                /** @var Collection AbstractUser*/
                $users = $user->getCompany()->getUsers();
                foreach ($users as $item) {
                    /** @var $item AbstractUser */
                    $item->setEnabled(false);
                    $this->em->persist($item);
                }
            }
        }

        $this->em->persist($user);
        $this->em->flush();

        return "success";
    }

    /**
     * Reset password manually for the user
     * @param integer $user_id
     * @return mixed
     */
    function reset_password($user_id)
    {
        /** @var AbstractUserRepository*/
        $repository_user = $this->em->getRepository('ApiBundle:User\AbstractUser');

        /** @var $user AbstractUser*/
        $user = $repository_user->findOneBy(array('id' => $user_id));

        if(empty($user)) {
            return $this->translator->trans("user_not_found");
        }

        $user->setPassword(null);

        $this->mailer->sendResettingEmailMessage($user);

        $this->em->persist($user);
        $this->em->flush();

        return "success";
    }

    /**
     * @param $custom_notification_id integer
     * @param $users array
     * @return mixed
     */
    public function choose_users($custom_notification_id, $users)
    {
        /** @var CustomNotificationRepository*/
        $custom_notification_repository = $this->em->getRepository('ApiBundle:Notification\CustomNotification');

        /** @var $custom_notification CustomNotification*/
        $custom_notification = $custom_notification_repository->findOneBy(array('id' => $custom_notification_id));

        if(empty($custom_notification)) {
            return $this->translator->trans("record_not_found");
        }

        /** @var AbstractUserRepository*/
        $repository_user = $this->em->getRepository('ApiBundle:User\AbstractUser');

        foreach ($users as $user) {
            /** @var $current_user AbstractUser*/
            $current_user = $repository_user->findOneBy(array('id' => $user));
            if(!empty($current_user)) {
                $custom_notification->addUser($current_user);
            }
            $this->em->persist($custom_notification);
            $this->em->flush();
        }

        return $custom_notification;
    }

    /**
     * @param $current_ip
     * @param AbstractUser $user
     * @return mixed
     */
    public function check_ip($user, $current_ip)
    {
        /** @var $alert_repository AlertRepository*/
        $alert_repository = $this->em->getRepository('ApiBundle:Notification\Alert');

        $ips = $alert_repository->findBy(array('user' => $user->getId()));

        foreach($ips as $ip) {
            /** @var $ip Alert*/
            if($ip->getIp() === $current_ip && !$ip->getConfirmed()) {
                return false;
            } elseif($ip->getIp() === $current_ip) {
                return true;
            }
        }

        $alert = new Alert();
        $alert->setUser($user);
        $alert->setIp($current_ip);
        $this->em->persist($alert);
        $this->em->flush();

        return false;

    }

    /**
     * @param Company $company
     * @return mixed
     */
    public function getStatistic($company)
    {
        /** @var $repository_user AbstractUserRepository*/
        $repository_user = $this->em->getRepository('ApiBundle:User\AbstractUser');

        /** @var $repository_device DeviceRepository */
        $repository_device = $this->em->getRepository('ApiBundle:User\Device\Device');

        /** @var $repository_admin AdminRepository */
        $repository_admin = $this->em->getRepository('ApiBundle:User\Admin');

        $users_online = $repository_user->getUsersOnline($company);

        /** @var $repository_branch BranchRepository*/
        $repository_branch = $this->em->getRepository('ApiBundle:Branch');

        $number_branches = count($repository_branch->findBy(['company' => $company->getId()]));

        $active_tablet_devices = $repository_user->getUsersOnline($company, true);

        return array(
            'users_online' => $users_online,
            'registered users' => $repository_admin->findBy(['company' => $company->getId()]),
            'number_branches' => $number_branches,
            'active_tablet_devices' => $active_tablet_devices,
            'installed apps' => count($repository_device->findBy(['company' => $company->getId()])),
            'monthly earnings' => $this->branch_service->getStatisticIncome(date("Y-m-01"), date("Y-m-d"), $company)
        );
    }

}