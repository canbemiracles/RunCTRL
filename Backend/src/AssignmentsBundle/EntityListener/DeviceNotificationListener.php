<?php

namespace AssignmentsBundle\EntityListener;


use ApiBundle\Entity\TimeZone;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Service\Subscription\SubscriptionLimits;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use AssignmentsBundle\Entity\Notification\DeviceNotification;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use AssignmentsBundle\Service\Manager\AssignmentManager;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use RedjanYm\FCMBundle\FCMClient;
use JMS\Serializer\Serializer;

class DeviceNotificationListener
{
    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var EntityManager */
    protected $entityManager;

    /** @var SubscriptionLimits */
    protected $limits;

    /** @var FCMClient */
    protected $fcm;

    /** @var Serializer */
    protected $serializer;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var RequestStack */
    protected $requestStack;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager, SubscriptionLimits $limits,
                                FCMClient $fcm, Serializer $serializer, TranslatorInterface $translator, RequestStack $request_stack)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->limits = $limits;
        $this->fcm = $fcm;
        $this->serializer = $serializer;
        $this->translator = $translator;
        $this->requestStack = $request_stack;
    }

    public function prePersist(DeviceNotification $notification, LifecycleEventArgs $event)
    {
        if(empty($this->tokenStorage->getToken())) {
            return ;
        }
        /** @var $user AbstractUser */
        $user = $this->tokenStorage->getToken()->getUser();

        if(!$user instanceof AbstractUser) {
            return;
        }

        $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

        if(!empty($notification->getStartTime())) {
            $start_time = $notification->getStartTime();
            $notification->setStartTime($start_time->modify("{$offset} hour"));
        }
        if(!empty($notification->getEndTime())) {
            $end_time = $notification->getEndTime();
            $notification->setEndTime($end_time->modify("{$offset} hour"));
        }

    }

    public function preUpdate(DeviceNotification $notification, LifecycleEventArgs $event)
    {
        if(empty($this->tokenStorage->getToken())) {
            return ;
        }
        /** @var $user AbstractUser */
        $user = $this->tokenStorage->getToken()->getUser();

        if(!$user instanceof AbstractUser) {
            return;
        }

        $changeSet = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet(
            $event->getEntity()
        );

        /** @var $request ParameterBag*/
        $request = $this->requestStack->getCurrentRequest()->request;

        $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

        if(!empty($notification->getStartTime()) && (!empty($changeSet['start_time']) || !empty($request->get('start_time')))) {
            $start_time = $notification->getStartTime();
            $notification->setStartTime($start_time->modify("{$offset} hour"));
        }
        if(!empty($notification->getEndTime()) && (!empty($changeSet['end_time']) || !empty($request->get('end_time')))) {
            $end_time = $notification->getEndTime();
            $notification->setEndTime($end_time->modify("{$offset} hour"));
        }
    }
}