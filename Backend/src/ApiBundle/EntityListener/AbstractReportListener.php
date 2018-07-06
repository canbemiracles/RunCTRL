<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 11.10.2017
 * Time: 17:01
 */

namespace ApiBundle\EntityListener;


use ApiBundle\Entity\Company;
use ApiBundle\Entity\Report\AbstractReport;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Repository\CompanyRepository;
use ApiBundle\Repository\User\AdminRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Guzzle\Common\Collection;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use WebSocketsBundle\Entity\Notification\ReportNotification;
use WebSocketsBundle\Service\NotificationService;

class AbstractReportListener
{
    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var EntityManager */
    protected $entityManager;

    /** @var NotificationService */
    protected $notificationService;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager, NotificationService $notificationService)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->notificationService = $notificationService;
    }

    public function prePersist(AbstractReport $report, LifecycleEventArgs $event)
    {

        /** @var $user AbstractUser*/
        $token = $this->tokenStorage->getToken();

        if(!$token) {
            return;
        }

        $user = $token->getUser();

        if(!$user instanceof AbstractUser) {
            return 0;
        }

        if(!empty($user->getCompany()) && $user->getCompany()->getPlan() == Company::PLAN_FREE) {
            $report->setDateDeleted(new \DateTime('+1 year'));
        }
    }

//    public function postPersist(AbstractReport $report, LifecycleEventArgs $event)
//    {
//        $token = $this->tokenStorage->getToken();
//
//        if(!$token)
//            return;
//        $this->notificationService->notifyAboutReport($report);
//    }
}