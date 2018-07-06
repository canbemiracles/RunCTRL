<?php

namespace WebSocketsBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\User\AbstractUser;
use Doctrine\Common\Collections\Collection;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use WebSocketsBundle\Entity\Notification\AbstractNotification;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use WebSocketsBundle\Entity\Notification\AlertNotification;
use WebSocketsBundle\Entity\Notification\AnnouncementNotification;
use WebSocketsBundle\Entity\Notification\ReportNotification;
use WebSocketsBundle\Repository\Notification\AbstractNotificationRepository;

/**
 * Abstractnotification controller.
 *
 * @Route("/users/{user_id}/notifications")
 */
class AbstractNotificationController extends AbstractController
{
    /**
     * @Security("is_granted('notifications_index')")
     * @ApiDoc(
     *  section="Abstractnotification",
     *  description="Lists all Abstractnotification entities.",
     *  output="WebSocketsBundle\Entity\Notification\AbstractNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="notifications_index")
     * @ParamConverter("user", options={"mapping": {"user_id" : "id"}})
     * @Rest\View()
     * @param AbstractUser $user
     * @param Request $request
     * @return array
     */
    public function indexAction(AbstractUser $user, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $repository AbstractNotificationRepository */
        $repository = $em->getRepository('WebSocketsBundle:Notification\AbstractNotification');

        /** @var $abstractNotifications Collection AbstractNotification */
        $abstractNotifications =  $repository->getAllNotificationByUser($user);

        $page = 1;

        if($request->query->get('page') != null) {
            $page = $request->query->get('page');
            $request->query->remove('page');
        }

        $pager = $this->get('knp_paginator');

        return $pager->paginate($abstractNotifications, $page, $this->getParameter('page_range')['notifications_per_page']);
    }

    /**
     * @Security("is_granted('notifications_show')")
     * @ApiDoc(
     *  section="Abstractnotification",
     *  description="One record Abstractnotification entity.",
     *  output="WebSocketsBundle\Entity\Notification\AbstractNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/{id}", name="notifications_show")
     * @ParamConverter("user", options={"mapping": {"user_id" : "id"}})
     * @Rest\View()
     * @param AbstractNotification $abstractNotification
     * @param AbstractUser $user
     * @return AbstractNotification
     */
    public function showAction(AbstractNotification $abstractNotification, AbstractUser $user)
    {
        $auth_user = $this->getUser();

        if(!$user->hasRole('ROLE_OWNER') && $auth_user->getCompany()->getId() !== $user->getCompanyId()) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.notification'));
        }

        return $abstractNotification;
    }

    /**
     * @Security("is_granted('notification_edit')")
     * @ApiDoc(
     *  section="Abstractnotification",
     *  description="Edits an existing notification message entity.",
     *  output="WebSocketsBundle\Entity\Notification\AbstractNotification",
     *  input="WebSocketsBundle\Form\Notification\BaseNotificationType",
     *  tags = {
     *    "Not Implemented" = "red",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="notification_put")
     * @Rest\Patch("/{id}", name="notification_patch")
     * @ParamConverter("user", options={"mapping": {"user_id" : "id"}})
     * @param Request $request
     * @param AbstractNotification $abstractNotification
     * @param AbstractUser $user
     * @return AbstractNotification|JsonResponse
     */
    public function editAction(Request $request, AbstractNotification $abstractNotification, AbstractUser $user)
    {
        $auth_user = $this->getUser();

        if((!$user->hasRole('ROLE_OWNER') && $auth_user->getCompany()->getId() !== $user->getCompanyId()) ||
            (($abstractNotification instanceof AlertNotification || $abstractNotification instanceof ReportNotification) && $abstractNotification->getUser() !== $user) ||
            ($abstractNotification instanceof AnnouncementNotification && !$abstractNotification->getUsers()->contains($user))) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.notification'));
        }

        $editForm = $this->createForm('WebSocketsBundle\Form\Notification\BaseNotificationType', $abstractNotification, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($abstractNotification);
            $em->flush();
            return $abstractNotification;
        } else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }
}
