<?php
/**
 * Created by PhpStorm.
 * User: Dego1n
 * Date: 11.10.2017
 * Time: 16:11
 */

namespace AssignmentsBundle\EntityListener;


use ApiBundle\Entity\TimeZone;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Entity\User\Device\Device;
use ApiBundle\Service\Subscription\SubscriptionLimits;
use AssignmentsBundle\Entity\Assignment\AbstractAssignment;
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

class AbstractAssignmentListener
{
    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var EntityManager */
    protected $entityManager;

    /** @var SubscriptionLimits */
    protected $limits;

    /** @var Serializer */
    protected $serializer;

    /** @var TranslatorInterface */
    protected $translator;

    /** @var AssignmentManager */
    protected $assignmentManager;

    /** @var RequestStack */
    protected $requestStack;

    public function __construct(TokenStorage $tokenStorage, EntityManager $entityManager, SubscriptionLimits $limits,
                                Serializer $serializer, TranslatorInterface $translator, AssignmentManager $manager,
                                RequestStack $request_stack)
    {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->limits = $limits;
        $this->serializer = $serializer;
        $this->translator = $translator;
        $this->assignmentManager = $manager;
        $this->requestStack = $request_stack;
    }

    public function prePersist(AbstractAssignment $assignment, LifecycleEventArgs $event)
    {
        if (empty($this->tokenStorage->getToken())) {
            return;
        }
        /** @var $user AbstractUser */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof AbstractUser) {
            return;
        }

        if (!$this->limits->canCreateAssignment($user)) {
            throw new BadRequestHttpException($this->translator->trans('you_not_allowed.create.more_assignment'));
        }

        $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

        if (!empty($assignment->getStartTime())) {
            $start_time = $assignment->getStartTime();
            $assignment->setStartTime($start_time->modify("{$offset} hour"));
        }
        if (!empty($assignment->getEndTime())) {
            $end_time = $assignment->getEndTime();
            $assignment->setEndTime($end_time->modify("{$offset} hour"));
        }

    }

    public function preUpdate(AbstractAssignment $assignment, LifecycleEventArgs $event)
    {
        if (empty($this->tokenStorage->getToken())) {
            return;
        }
        /** @var $user AbstractUser */
        $user = $this->tokenStorage->getToken()->getUser();

        if (!$user instanceof AbstractUser) {
            return;
        }

        $changeSet = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet(
            $event->getEntity()
        );

        /** @var $request ParameterBag*/
        $request = $this->requestStack->getCurrentRequest()->request;

        $offset = -intval($user->getCompany()->getTimeZone()->getOffset());

        if (!empty($assignment->getStartTime()) && (!empty($changeSet['start_time']))) {
            $start_time = $assignment->getStartTime();
            $assignment->setStartTime($start_time->modify("{$offset} hour"));
        } elseif(!empty($request->get('start_time'))) {
            $start_time = new \DateTime($request->get('start_time'));
            $assignment->setStartTime($start_time->modify("{$offset} hour"));
        }
        if (!empty($assignment->getEndTime()) && (!empty($changeSet['end_time']))) {
            $end_time = $assignment->getEndTime();
            $assignment->setEndTime($end_time->modify("{$offset} hour"));
        } elseif(!empty($request->get('end_time'))) {
            $start_time = new \DateTime($request->get('start_time'));
            $assignment->setStartTime($start_time->modify("{$offset} hour"));
        }
    }

    public function postPersist(AbstractAssignment $assignment, LifecycleEventArgs $event)
    {
        if ($assignment->getRole() == null || $assignment->getStartTime() == null) {
            return;
        }

        $this->assignmentManager->handleNewAndRepeatableAssignments();
    }

    public function postUpdate(AbstractAssignment $assignment, LifecycleEventArgs $event)
    {
        if ($assignment->getRole() == null || $assignment->getStartTime() == null) {
            return;
        }

        $changeSet = $event->getEntityManager()->getUnitOfWork()->getEntityChangeSet(
            $event->getEntity()
        );

        if (!empty($assignment->getStartTime()) && !empty($changeSet['start_time'])) {
            $this->assignmentManager->handleNewAndRepeatableAssignments();
        }
    }
}