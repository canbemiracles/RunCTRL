<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 11.10.2017
 * Time: 16:11
 */

namespace ApiBundle\EntityListener;


use ApiBundle\Entity\BranchShift;
use ApiBundle\Entity\TimeZone;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Repository\BranchShiftRepository;
use ApiBundle\Service\Subscription\SubscriptionLimits;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
use AssignmentsBundle\Repository\Assignment\AbstractAssignmentRepository;
use AssignmentsBundle\Service\Manager\AssignmentManager;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use RedjanYm\FCMBundle\FCMClient;
use JMS\Serializer\Serializer;

class BranchShiftListener
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

    /** @var BranchShift */
    protected $branchShift;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager, SubscriptionLimits $limits,
                                FCMClient $fcm, Serializer $serializer, TranslatorInterface $translator)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->limits = $limits;
        $this->fcm = $fcm;
        $this->serializer = $serializer;
        $this->translator = $translator;
    }

    public function prePersist(BranchShift $branchShift, LifecycleEventArgs $event)
    {
        if(empty($this->tokenStorage->getToken())) {
            return;
        }
        /** @var $user AbstractUser */
        $user = $this->tokenStorage->getToken()->getUser();

        if(!$this->limits->canCreateAssignment($user)) {
            throw new BadRequestHttpException($this->translator->trans('you_not_allowed.create.more_assignment'));
        }

        if(!empty($user->getCompany()) && !empty($user->getCompany()->getTimeZone())) {
            $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

            if (!empty($branchShift->getTimeOpen())) {
                $timeOpen = $branchShift->getTimeOpen();
                $branchShift->setTimeOpen($timeOpen->modify("{$offset} hour"));
            }
            if (!empty($branchShift->getTimeClose())) {
                $timeClose = $branchShift->getTimeClose();
                $branchShift->setTimeClose($timeClose->modify("{$offset} hour"));
            }
        }
        /** @var $repository BranchShiftRepository*/
        $repository = $this->entityManager->getRepository('ApiBundle\Entity\BranchShift');
        if($repository->checkShift($branchShift->getTimeOpen(), $branchShift->getTimeClose(), $branchShift->getBranch(), $branchShift->getShiftDay()) &&
            !empty($branchShift->getTimeOpen()) && !empty($branchShift->getTimeClose()) && !empty($branchShift->getShiftDay())) {
            throw new BadRequestHttpException($this->translator->trans('branch_shift_error'));
        }
    }

    public function preUpdate(BranchShift $branchShift, LifecycleEventArgs $event)
    {
        if(empty($this->tokenStorage->getToken())) {
            return;
        }
        /** @var $user AbstractUser */
        $user = $this->tokenStorage->getToken()->getUser();

        if(!$user instanceof AbstractUser) {
            return;
        }

        $changeSet = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet(
            $event->getEntity()
        );

        $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

        if(!empty($branchShift->getTimeOpen()) && !empty($changeSet['time_open'])) {
            $timeOpen = $branchShift->getTimeOpen();
            $branchShift->setTimeOpen($timeOpen->modify("{$offset} hour"));
        }

        if(!empty($branchShift->getTimeClose()) && !empty($changeSet['time_close'])) {
            $timeClose = $branchShift->getTimeClose();
            $branchShift->setTimeClose($timeClose->modify("{$offset} hour"));
        }

        /** @var $repository BranchShiftRepository*/
        $repository = $this->entityManager->getRepository('ApiBundle\Entity\BranchShift');
        $check_shift = current($repository->checkShift($branchShift->getTimeOpen(), $branchShift->getTimeClose(), $branchShift->getBranch(), $branchShift->getShiftDay()));
        if(!empty($check_shift) && $check_shift !== $branchShift && !empty($branchShift->getTimeOpen()) && !empty($branchShift->getTimeClose()) && !empty($branchShift->getShiftDay())) {
            throw new BadRequestHttpException($this->translator->trans('branch_shift_error'));
        }
    }

}