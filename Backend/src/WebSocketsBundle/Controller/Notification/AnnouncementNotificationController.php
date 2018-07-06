<?php

namespace WebSocketsBundle\Controller\Notification;

use ApiBundle\Controller\AbstractController;
use ApiBundle\Entity\Company;
use ApiBundle\Entity\Notification\CustomNotification;
use ApiBundle\Entity\User\AbstractUser;
use ApiBundle\Service\AdminPanel\UsersManagementService;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use WebSocketsBundle\Entity\Notification\AnnouncementNotification;
use WebSocketsBundle\Service\NotificationService;

/**
 * AnnouncementNotification controller.
 *
 * @Route("company/{company_id}/announcement_notifications")
 */

class AnnouncementNotificationController extends AbstractController
{
    /**
     * @Security("is_granted('announcement_notifications_index')")
     * @ApiDoc(
     *  section="AnnouncementNotification",
     *  description="Lists all AnnouncementNotification entities.",
     *  output="WebSocketsBundle\Entity\Notification\AnnouncementNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Get("/", name="announcement_notifications_index")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Company $company
     * @return array|Collection AnnouncementNotification
     */
    public function indexAction(Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.these.custom_notifications'));
        }

        return $company->getAnnouncementNotifications();
    }

    /**
     * @Security("is_granted('announcement_notification_new')")
     * @ApiDoc(
     *  section="AnnouncementNotification",
     *  description="Creates a new AnnouncementNotification entity.",
     *  input="WebSocketsBundle\Form\Notification\AnnouncementNotificationType",
     *  output="WebSocketsBundle\Entity\Notification\AnnouncementNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\Post("/new", name="announcement_notification_new")
     * @Rest\View()
     * @param Request $request
     * @param Company $company
     * @return mixed|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function newAction(Request $request, Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.create.custom_notifications'));
        }

        $form = $this->createForm('WebSocketsBundle\Form\Notification\AnnouncementNotificationType',  new AnnouncementNotification());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $announcementNotification = $form->getData();
            $announcementNotification->setCompany($company);
            $em->persist($announcementNotification);
            $em->flush();

            return $announcementNotification;
        }
        else {
            return $this->getJsonErrorsResponse($form);
        }
    }


    /**
     * @Security("is_granted('announcement_notification_show')")
     * @ApiDoc(
     *  section="AnnouncementNotification",
     *  description="Finds and displays a AnnouncementNotification entity.",
     *  output="WebSocketsBundle\Entity\Notification\AnnouncementNotification",
     *  tags = {
     *    "Implemented" = "green"
     *  },
     * )
     * @Rest\Get("/{id}", name="announcement_notification_show")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param AnnouncementNotification $announcement_notification
     * @param Company $company
     * @return object
     */
    public function showAction(AnnouncementNotification $announcement_notification, Company $company)
    {
        if(!$announcement_notification){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.see.this.custom_notifications'));
        }

        return $announcement_notification;
    }

    /**
     * @Security("is_granted('announcement_notification_edit')")
     * @ApiDoc(
     *  section="AnnouncementNotification",
     *  description="Displays a form to edit an existing AnnouncementNotification entity.",
     *  output="WebSocketsBundle\Entity\Notification\AnnouncementNotification",
     *  input="WebSocketsBundle\Form\Notification\AnnouncementNotificationType",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\View()
     * @Rest\Put("/{id}", name="announcement_notification_put")
     * @Rest\Patch("/{id}", name="announcement_notification_patch")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @param Request $request
     * @param AnnouncementNotification $announcement_notification
     * @param Company $company
     * @return AnnouncementNotification | \Symfony\Component\HttpFoundation\JsonResponse|Response
     */
    public function editAction(Request $request, AnnouncementNotification $announcement_notification, Company $company)
    {
        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.custom_notifications'));
        }

        $editForm = $this->createForm('WebSocketsBundle\Form\Notification\AnnouncementNotificationType', $announcement_notification, ['method' => $request->getMethod()]);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $announcement_notification;
        }
        else {
            return $this->getJsonErrorsResponse($editForm);
        }
    }

    /**
     * @Security("is_granted('announcement_notification_delete')")
     * @ApiDoc(
     *  section="AnnouncementNotification",
     *  description="Deletes a AnnouncementNotification entity.",
     *  output="WebSocketsBundle\Entity\Notification\AnnouncementNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Delete("/{id}", name="announcement_notification_delete")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param AnnouncementNotification $announcement_notification
     * @param Company $company
     * @return Response
     */
    public function deleteAction(Request $request, AnnouncementNotification $announcement_notification, Company $company)
    {
        if(!$announcement_notification){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.delete.custom_notifications'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($announcement_notification);
        $em->flush();

        return new Response();
    }

    /**
     * @Security("is_granted('announcement_notification_choose_users')")
     * @ApiDoc(
     *  section="AnnouncementNotification",
     *  description="",
     *  output="WebSocketsBundle\Entity\Notification\AnnouncementNotification",
     *  tags = {
     *    "Implemented" = "green",
     *  },
     * )
     * @Rest\Post("/choose_users", name="announcement_notification_choose_users")
     * @ParamConverter("company", options={"mapping": {"company_id" : "id"}})
     * @Rest\View()
     * @param Request $request
     * @param AnnouncementNotification $announcement_notification
     * @param Company $company
     * @return Response
     */
    public function chooseUsersAction(Request $request, AnnouncementNotification $announcement_notification, Company $company)
    {
        if(!$announcement_notification){
            throw new NotFoundHttpException($this->get('translator')->trans('record_not_found'));
        }

        $user = $this->getUser();

        if(!$user->hasRole(AbstractUser::ROLE_OWNER) && !$company->getUsers()->contains($user)) {
            throw new AccessDeniedHttpException($this->get('translator')->trans('you_not_allowed.edit.custom_notifications'));
        }

        /** @var $service NotificationService*/
        $service = $this->get('service.notification_service');
        return $service->choose_users($request->get('notification'), $request->get('users'));

    }

}
